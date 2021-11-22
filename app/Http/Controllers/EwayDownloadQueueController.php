<?php

namespace App\Http\Controllers;

use App\Models\EwayDownloadQueue;
use App\Jobs\DownloadEwayBillJob;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\DB;

class EwayDownloadQueueController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\EwayDownloadQueue  $ewayDownloadQueue
   * @return \Illuminate\Http\Response
   */
  public function show(EwayDownloadQueue $ewayDownloadQueue) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\EwayDownloadQueue  $ewayDownloadQueue
   * @return \Illuminate\Http\Response
   */
  public function edit(EwayDownloadQueue $ewayDownloadQueue) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\EwayDownloadQueue  $ewayDownloadQueue
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, EwayDownloadQueue $ewayDownloadQueue) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\EwayDownloadQueue  $ewayDownloadQueue
   * @return \Illuminate\Http\Response
   */
  public function destroy(EwayDownloadQueue $ewayDownloadQueue) {
    //
  }

  public function download(Request $request) {
    Cache::flush();
    DB::table('jobs')->where('queue', '=', 'download')->delete();
    $days = (int)env('DOWNLOAD_EWAYBILS_OF_LAST_DAYS', 30);
    $start_date = Carbon::now()->subDays($days)->format('d/m/Y');
    $end_date = Carbon::now()->format('d/m/Y');
    for ($i=$days;$i>=0;$i--) {
      $d = Carbon::now()->subDays($days-$i)->format('d/m/Y');
      DownloadEwayBillJob::dispatch($d)->onQueue('download');
    }
    return redirect()->back()->withSuccess('Download has been started. Please come back after 30 mins.');
  }
}
