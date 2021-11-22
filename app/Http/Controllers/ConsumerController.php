<?php

namespace App\Http\Controllers;

use App\Models\Consumer;
use App\Models\State;
use Illuminate\Http\Request;
use DataTables;
use Debugbar;
use Validator;

class ConsumerController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request) {
    $consumer = Consumer::with('states');

    if ($request->ajax()) {
      return $dtb =  DataTables::eloquent($consumer)
                      ->addColumn('state_name', function ($consumer) {
                        return $consumer->getState()->getName();
                      })
                      ->removeColumn(['updated_at','created_at'])->toJson();
    }

    return view('consumers.list');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    return view('consumers.createOrUpdate', ['data'=> new Consumer(),'states'=>State::all()]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $validator = Validator::make($request->all(), Consumer::$rules);

    if ($validator->fails()) {
      return redirect('consumer/create')
                  ->withErrors($validator)
                  ->withInput();
    }
    

    consumer::create($request->all());
    return redirect('consumer')->withSuccess('Consumer added successfully.');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Consumer  $consumer
   * @return \Illuminate\Http\Response
   */
  public function show(Consumer $consumer) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Consumer  $consumer
   * @return \Illuminate\Http\Response
   */
  public function edit($id) {
    $consumer = Consumer::findOrFail($id);
    return view('consumers.createOrUpdate', ['data'=>$consumer,'states'=>State::all()]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Consumer  $consumer
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Consumer $consumer) {
    $rules = Consumer::$rules;
    $rules['gstin'] = 'required|unique:consumers,gstin,'.$request->id;
    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return redirect('consumer/'.$request->id.'/edit')
                  ->withErrors($validator)
                  ->withInput();
    }
    $consumer = Consumer::find($request->id)->update($request->all());
    if ($consumer) {
      return redirect('consumer')->withSuccess('Consumer updated successfully.');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Consumer  $consumer
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    $consumer = Consumer::findOrFail($id);
    if ($consumer) {
      $consumer->delete();
      return redirect('consumer')->withSuccess('Consumer deleted successfully.');
    }
    return redirect('consumer')->withSuccess('Consumer not found.');
  }
}
