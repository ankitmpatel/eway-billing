<?php

namespace App\Http\Controllers;

use App\Libraries\MasterIndia\EwayBills;
use App\Libraries\MasterIndia\EwaybillExtend;
use App\Libraries\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\EwayBillExtendReasons;
use App\Models\EwayDownloadQueue;
use App\Models\EwayBillExtendQueue;
use App\Models\State;
// use App\Events\ExtendEwayBillProcessed;
use App\Jobs\ExtendEwayBillJob;
use Illuminate\Support\Str;
use Debugbar;
use DataTables;
use Carbon\Carbon;

class EwayController extends Controller {

  /**
   * eWay Bill Listings
   */

  public function all(Request $request) {
    Debugbar::info(Session::get('session_eway_ids'));
    $ewayObj = new EwayBills();
        
    // API Required Params
    $documentNumber = $request->input('document_number')??'';
    $gstin = $request->input('gstin_of_consignor')??'05AAABB0639G1Z8';
    $documentDate = Helper::dateFormatIndia($request->input('document_date'))??'';
        
    // $eWayBills = $ewayObj->getEwayBillLists($documentNumber, $gstin, $documentDate, $generateStatus=1, $page=1, $limit=20000);
    // Debugbar::info($eWayBills);

    if ($request->ajax()) {
      $eWayBills = EwayDownloadQueue::where('eway_bill_valid_date', 'like', '%'.$documentDate.'%')->get()->map(function ($e) { return json_decode($e->response, true); })->toArray();
  
      if (isset($eWayBills['error'])) {
        return $eWayBills['error'];
      }

      return  DataTables::of($eWayBills)
                ->toJson();
    }
    return view('eway/all');
  }

  public function index(Request $request) {
    Debugbar::info(Session::get('session_eway_ids'));
    $ewayObj = new EwayBills();
        
    // API Required Params
    $documentNumber = $request->input('document_number')??'';
    $gstin = $request->input('gstin_of_consignor')??'05AAABB0639G1Z8';
    $documentDate = Helper::dateFormatIndia($request->input('document_date'))??'';

    $today = Carbon::today(Helper::getTimeZone())->format('d/m/Y');
    $tomorrow = Carbon::tomorrow(Helper::getTimeZone())->format('d/m/Y');

    $today_c = EwayDownloadQueue::where('eway_bill_valid_date', 'like', '%'.$today.'%')->count();
    $tomorrow_c = EwayDownloadQueue::where('eway_bill_valid_date', 'like', '%'.$tomorrow.'%')->count();
    
    if ($request->ajax()) {
      $eWayBills = EwayDownloadQueue::where('eway_bill_valid_date', 'like', '%'.$documentDate.'%')
                    ->where('response', 'not like', '%"vehicle_number":""%')
                    ->get()
                    ->map(function ($e) { return json_decode($e->response, true); })
                    ->toArray();
  
      if (isset($eWayBills['error'])) {
        return $eWayBills['error'];
      }
      return  DataTables::of($eWayBills)
                ->addColumn('session_ewaybill', Helper::getKeysFromSessionObject('session_eway_ids'))
                ->toJson();
    }
    return view('eway/list', ['counter'=>['today'=>$today_c, 'tomorrow'=>$tomorrow_c]]);
  }

  public function getEwayBillDetails($ewayBillId, $gstin) {
    $ewayObj = new EwayBills();
    $ewayBillDetail = $ewayObj->getEwayBill($ewayBillId, $gstin);
    
    if (empty($ewayBillDetail['message'])) {
      return abort(503, 'Unauthorized action.');
    }
    
    return view('eway/detail', ['billDetails'=>$ewayBillDetail['message']]);
  }


  public function updateVehicle(Request $request) {
    return view('eway.vehicle.update');
  }

  public function collectEwayBillIds(Request $request, $id, $action) {
    if ($action=="true") {
      Helper::addValueSessionObject('session_eway_ids', $id, $request->input('eway_data'));
    } else {
      Helper::removeValueSessionObject('session_eway_ids', $id);
    }
  }

  public function collectEwayBillIdsMultiple(Request $request) {
    $request->session()->forget('session_eway_ids');
    if ($request->input('eway_data')) {
      foreach ($request->input('eway_data') as $e) {
        $eway = json_decode(urldecode($e))->eway_bill_number;
        Helper::addValueSessionObject('session_eway_ids', $eway, $e);
      }
      return true;
    }
    return false;
  }



  /**
   * eWay Bill Extends
   */

  public function extendList(Request $request) {
    $session_ewaybill_data = Session::get('session_eway_ids')??[];
    $session_ewaybill_array = [];
    foreach ($session_ewaybill_data as $k=>$d) {
      $session_ewaybill_array[$k] = json_decode($d, true);
    }

    $get_extend_reasons_list = EwayBillExtendReasons::pluck('reason_name')
                                                      ->toArray();

    $get_states_list = State::pluck('name')
                              ->toArray();

    return view('eway.extend.create', ['ewayBills'=>$session_ewaybill_array,'extend_reasons_list'=>$get_extend_reasons_list,'state_list'=>$get_states_list]);
  }

  public function extendSend(Request $request) {
    $ewayData = $request->input('data');
    $batchId = Str::random(9);
    $queue_type = EwayBillExtendQueue::DEFAULT;

    $originalBatchDateTime = Carbon::now()->format('Y-m-d H:i:00');
    
    foreach ($ewayData as $e) {
      $e['batch_id'] = $batchId;
    
      if ((bool)$request->input('extend')) {
        $datef = $request->session()->get('schedule_datetime');
        if ($datef) {
          $delayDate = Carbon::createFromFormat('Y-m-d H:i', $datef." ".env('AUTO_SCHEDULER_TIME'), Helper::getTimeZone());
          ExtendEwayBillJob::dispatch($e)->delay($delayDate);
          $queue_type = EwayBillExtendQueue::EWAYBILL_FUTURE_EXTEND;
        }
        $batchDateTime = $delayDate->format('Y-m-d H:i:00');
      } else {
        ExtendEwayBillJob::dispatch($e);
        $batchDateTime = $originalBatchDateTime;
        $queue_type = EwayBillExtendQueue::EWAYBILL_EXTEND;
      }
    
      EwayBillExtendQueue::create([
            'eway_bill_number'=>$e['eway_bill_number'],
            'batch_id'=>$e['batch_id'],
            'batch_datetime'=>$batchDateTime,
            'request'=>json_encode($e),
            'response'=>'',
            'status'=>EwaybillExtend::$pending,
            'original_request'=>Session::get('session_eway_ids')[$e['eway_bill_number']],
            'queue_type' => $queue_type
      ]);
      Helper::removeValueSessionObject('session_eway_ids', $e['eway_bill_number']);
    }

    return redirect()->route('ewaybill.extend')->withSuccess('Selected Eway Bills are added to queue.');
  }
}
