<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return redirect('login');
});

Auth::routes(['verify' => true]);
Route::middleware(['auth'])->group(function () {
  Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
  Route::get('/users', [App\Http\Controllers\HomeController::class, 'getUsers'])->name('get.users');
  Route::get('/user/add', [App\Http\Controllers\HomeController::class, 'addUser'])->name('add.user');

  // Transporter
  Route::resource('transporter', TransporterController::class)->except([
    'destroy','update'
  ]);
  Route::get('transporter/{id}/delete', [App\Http\Controllers\TransporterController::class, 'destroy'])->name('transporter.destroy');
  Route::post('transporter/{id}/update', [App\Http\Controllers\TransporterController::class, 'update'])->name('transporter.update');
  Route::get('transporters', [App\Http\Controllers\TransporterController::class, 'getTransporters'])->name('transporter.get.ajax');

  // Vehicle
  Route::resource('vehicle', VehicleController::class)->except([
    'destroy','update'
  ]);
  Route::get('vehicle/{id}/delete', [App\Http\Controllers\VehicleController::class, 'destroy'])->name('vehicle.destroy');
  Route::post('vehicle/{id}/update', [App\Http\Controllers\VehicleController::class, 'update'])->name('vehicle.update');

  // Consumer
  Route::resource('consumer', ConsumerController::class)->except([
    'destroy','update'
  ]);
  Route::get('consumer/{id}/delete', [App\Http\Controllers\ConsumerController::class, 'destroy'])->name('consumer.destroy');
  Route::post('consumer/{id}/update', [App\Http\Controllers\ConsumerController::class, 'update'])->name('consumer.update');

  // Eway Bill
  Route::get('/ewaybill', [App\Http\Controllers\EwayController::class, 'index'])->name('ewaybill.index');
  Route::get('/ewaybill/all', [App\Http\Controllers\EwayController::class, 'all'])->name('ewaybill.all');
  Route::get('/ewaybill/update/vehicle', [App\Http\Controllers\EwayController::class, 'updateVehicle'])->name('ewaybill.update.vehicle');
  Route::get('/ewaybill/detail/{id}/{gstin}', [App\Http\Controllers\EwayController::class, 'getEwayBillDetails'])->name('ewaybill.detail');
  
  Route::get('/ewaybill/extend', [App\Http\Controllers\EwayController::class, 'extendList'])->name('ewaybill.extend');
  Route::get('/ewaybill/extend/schedule', [App\Http\Controllers\EwayController::class, 'extendList'])->name('ewaybill.extend.schedule');
  Route::post('/ewaybill/extend/send', [App\Http\Controllers\EwayController::class, 'extendSend'])->name('ewaybill.extend.send');

  // Extend 
  Route::get('/ewaybillextendqueue', [App\Http\Controllers\EwayBillExtendQueueController::class, 'index'])->name('ewaybillextendqueue.index');
  Route::get('/ewaybillextendqueue/autoschedule', [App\Http\Controllers\EwayBillExtendQueueController::class, 'autoschedule'])->name('ewaybillextendqueue.autoschedule');
  Route::post('/ewaybillextendqueue/autoschedule/list', [App\Http\Controllers\EwayBillExtendQueueController::class, 'autoScheduleList'])->name('ewaybillextendqueue.autoschedule.list');
  Route::get('/ewaybillextendqueue/autoschedule/list', [App\Http\Controllers\EwayBillExtendQueueController::class, 'autoScheduleList'])->name('ewaybillextendqueue.autoschedule.list');

  // Eway Bill AJAX
  Route::post('/ewaybill/collect_ids/{id}/{action}', [App\Http\Controllers\EwayController::class, 'collectEwayBillIds'])->name('ajax.ewaybill.collectids');
  Route::post('/ewaybill/collect_ids', [App\Http\Controllers\EwayController::class, 'collectEwayBillIdsMultiple'])->name('ajax.ewaybill.collectids.multiple');

  // Update Transporter
  Route::get('/updatetransporter/ewaybill', [App\Http\Controllers\UpdateTransporterController::class, 'index'])->name('updatetranspoerter.index');
  Route::get('/updatetransporter/update', [App\Http\Controllers\UpdateTransporterController::class, 'update'])->name('updatetranspoerter.update');

  // Download
  Route::get('/ewaybilldownloadqueue', [App\Http\Controllers\EwayDownloadQueueController::class, 'download'])->name('ewaybilldownloadqueue.index');
});