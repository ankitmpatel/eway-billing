<?php

namespace App\Http\Controllers;

use App\Models\Transporter;

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
use Validator;

class UpdateTransporterController extends Controller {

  public function index(Request $request) {
    Debugbar::info(Session::get('session_eway_ids'));
    $ewayObj = new EwayBills();
        
    if ($request->ajax()) {
      $eWayBills = EwayDownloadQueue::where('eway_bill_valid_date_unix', '>=', Carbon::now()->format('Y-m-d'))
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
    return view('updatetransporter/list');
  }


  public function update(Request $request) {
    $session_ewaybill_data = Session::get('session_eway_ids')??[];
    $session_ewaybill_array = [];
    foreach ($session_ewaybill_data as $k=>$d) {
      $session_ewaybill_array[$k] = json_decode($d, true);
    }

    $transporters = []; //Transporter::all()->toArray();

    $get_states_list = State::pluck('name')
                              ->toArray();

    return view('updatetransporter.create', ['ewayBills'=>$session_ewaybill_array,'transporters'=>$transporters]);
  }


}