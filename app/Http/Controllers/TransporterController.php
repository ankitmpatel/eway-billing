<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use Illuminate\Http\Request;
use DataTables;
use Debugbar;
use Validator;

class TransporterController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request) {
    $users = Transporter::query();

    if ($request->ajax()) {
      return $dtb =  DataTables::eloquent($users)->removeColumn(['updated_at','created_at'])->toJson();
    }

    return view('transporters.list');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    return view('transporters.createOrUpdate', ['data'=> new Transporter()]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $validator = Validator::make($request->all(), Transporter::$rules);
    if ($validator->fails()) {
      return redirect('transporter/create')
                  ->withErrors($validator)
                  ->withInput();
    }
    Transporter::create($request->all());
    return redirect('transporter')->withSuccess('Trasporter added successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Transporter  $transporter
   * @return \Illuminate\Http\Response
   */
  public function show(Transporter $transporter) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Transporter  $transporter
   * @return \Illuminate\Http\Response
   */
  public function edit($id) {
    $transporter = Transporter::findOrFail($id);
    Debugbar::info($transporter);
    return view('transporters.createOrUpdate', ['data'=>$transporter]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Transporter  $transporter
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Transporter $transporter) {
    $rules = Transporter::$rules;
    $rules['gstin'] = 'required|unique:transporters,gstin,'.$request->id;
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return redirect('transporter/'.$request->id.'/edit')
                  ->withErrors($validator)
                  ->withInput();
    }
    $transporter = Transporter::find($request->id)->update($request->all());
    if ($transporter) {
      return redirect('transporter')->withSuccess('Trasporter updated successfully.');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Transporter  $transporter
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    $transporter = Transporter::findOrFail($id);
    if ($transporter) {
      $transporter->delete();
      return redirect('transporter')->withSuccess('Trasporter deleted successfully.');
    }
    return redirect('transporter')->withSuccess('Trasporter not found.');
  }

  public function getTransporters(Request $request) {
    $transporters = Transporter::select('id','gstin','name')->take(10)->get()->toArray();

    if($request->input('term')!=''){
      $term = $request->input('term');
      $transporters = Transporter::select('id','gstin','name')->where('name','like','%'.$term.'%')->take(10)->get()->toArray();
    }
    return $transporters;
  }
}
