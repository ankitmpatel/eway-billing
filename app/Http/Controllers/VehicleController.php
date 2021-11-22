<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Transporter;
use Illuminate\Http\Request;
use DataTables;
use Debugbar;
use Validator;

class VehicleController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request) {
    $vehicle = Vehicle::all();
    $vehicle->each(function ($v) {
      $v->transporter = $v->getTransporter()->getName();
      return $v;
    });

    if ($request->ajax()) {
      return $dtb =  DataTables::collection($vehicle)->removeColumn(['updated_at','created_at'])->toJson();
    }
    
    return view('vehicles.list');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    $transporters = Transporter::all();
    return view('vehicles.createOrUpdate', ['data'=> new Vehicle(),'transporters'=>$transporters]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $validator = Validator::make($request->all(), Vehicle::$rules);
    if ($validator->fails()) {
      return redirect('vehicle/create')
                        ->withErrors($validator)
                        ->withInput();
    }
    $v = Vehicle::create($request->all());
    $v->transporters()->attach($request->input('transporter_id'));
    return redirect('vehicle')->withSuccess('Vehicle added successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\vehicle  $vehicle
   * @return \Illuminate\Http\Response
   */
  public function show(vehicle $vehicle) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\vehicle  $vehicle
   * @return \Illuminate\Http\Response
   */
  public function edit($id) {
    $vehicle = Vehicle::with('transporters')->findOrFail($id);
    $transporters = Transporter::all();
    Debugbar::info($vehicle->getTransporter());
    return view('vehicles.createOrUpdate', ['data'=>$vehicle,'transporters'=>$transporters]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\vehicle  $vehicle
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, vehicle $vehicle) {
    $validator = Validator::make($request->all(), Vehicle::$rules);
    if ($validator->fails()) {
      return redirect('vehicle/'.$request->id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
    }
    $v = Vehicle::find($request->id);
    $v->vehicle_number = $request->input('vehicle_number');
    $v->save();
    $t = $v->transporters();
    $t->detach();
    $t->attach($request->input('transporter_id'));
    return redirect('vehicle')->withSuccess('Trasporter updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\vehicle  $vehicle
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    $vehicle = Vehicle::findOrFail($id);
    if ($vehicle) {
      $vehicle->delete();
      return redirect('vehicle')->withSuccess('Vehicle deleted successfully.');
    }
    return redirect('vehicle')->withSuccess('Vehicle not found.');
  }
}
