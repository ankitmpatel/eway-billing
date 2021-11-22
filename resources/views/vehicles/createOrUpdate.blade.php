@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Vehicle</h1>
@stop

@section('content')
    <div class="row">
    <div class="col-4"></div>
    <div class="col-4">
    @if(session()->has('success'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{session()->get('success')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
<form method="POST" action="{{ $data->id == null ? route('vehicle.store') :  route('vehicle.update',[$data->id]) }}">
    @csrf
  
    <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">{{ $data->id == null ? 'Add' : 'Update' }} vehicle</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="tname">Vehicle Number <span class='text-danger'>*</span></label>
                    <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror"  id="vehicle_number" name="vehicle_number" placeholder="Enter Vehicle Number" value="{{ old('vehicle_number', $data->vehicle_number) }}">
                    @error('name')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="gstn">Transporter <span class='text-danger'>*</span></label>
                    <select class="form-control select2 trasporters" name="transporter_id">
                    @foreach($transporters as $t)
                      <option value="{{$t->getId()}}" @if(($data->getTransporter()!=null) && $data->getTransporter()->getId()==$t->getId()) selected='selected' @endif>{{ $t->getName() }}</option>
                    @endforeach
                    </select>
                    @error('transporter_id')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                 
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="{{route('vehicle.index')}}" class="btn btn-secondary">Cancel</a>
                </div>
              </form>
            </div>
</form>
</div>
<div class="col-4"></div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop



@section('adminlte_js')
<script>
$(document).ready(function() {
    $('.trasporters').select2();
});
</script>
@stop
