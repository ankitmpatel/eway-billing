<?php

namespace App\Http\Controllers;

use App\Models\EwayBillExtendQueue;
use App\Libraries\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\EwayDownloadQueue;
use DataTables;
use Carbon\Carbon;

class EwayBillExtendQueueController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request) {
    $batches = EwayBillExtendQueue::orderByDesc('created_at')->whereDate('batch_datetime', carbon::now()->format('Y-m-d'))->pluck('batch_datetime')->unique();
    if ($request->ajax()) {
      $queue = EwayBillExtendQueue::query();
      $queue->whereDate('batch_datetime', carbon::now()->format('Y-m-d'));
      $queue->orderByDesc('created_at');

      return  DataTables::of($queue)
                    ->filterColumn('batch_datetime', function ($query, $keyword) use ($batches) {
                      if ($keyword=='') {
                        $keyword = $batches->first();
                      }
                      $query->where('batch_datetime', $keyword);
                    })
                    ->addColumn('session_ewaybill', Helper::getKeysFromSessionObject('session_eway_ids'))
                    ->toJson();
    }
    return view('ewaybillextendqueue/list', ['batches'=>$batches]);
  }

  public function create() {
    //
  }


  public function store(Request $request) {
    //
  }


  public function show(EwayBillExtendQueue $ewayBillExtendQueue) {
    //
  }

  public function edit(EwayBillExtendQueue $ewayBillExtendQueue) {
    //
  }

  public function update(Request $request, EwayBillExtendQueue $ewayBillExtendQueue) {
    //
  }

  public function destroy(EwayBillExtendQueue $ewayBillExtendQueue) {
    //
  }

  public function autoschedule(Request $request) {
    return view('ewaybillextendqueue/autoschedule');
  }

  public function autoScheduleList(Request $request) {
    $date = '';
      
    if ($request->has('schedule_datetime')) {
      $date = $request->input('schedule_datetime');
      $request->session()->put('schedule_datetime', $date);
    }
    if ($request->session()->has('schedule_datetime')) {
      $date = $request->session()->get('schedule_datetime', '');
    }
    if ($date=='') {
      return redirect()->back()->withErrors(['Schedule date is not selected']);
    }

    $datef = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
      
    $eWayBills = EwayDownloadQueue::where('eway_bill_valid_date', 'like', '%'.$datef.'%')
                      ->where('response', 'not like', '%"vehicle_number":""%')
                      ->get()
                      ->map(function ($e) { return json_decode($e->response, true); })
                      ->toArray();

    if ($request->ajax()) {
      if (isset($eWayBills['error'])) {
        return $eWayBills['error'];
      }
      return  DataTables::of($eWayBills)
                  ->addColumn('session_ewaybill', Helper::getKeysFromSessionObject('session_eway_ids'))
                  ->toJson();
    }
    return view('ewaybillextendqueue/autoschedule-list');
  }
}
