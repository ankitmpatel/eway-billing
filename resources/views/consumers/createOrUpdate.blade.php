@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Consumer</h1>
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
<form method="POST" action="{{ $data->id == null ? route('consumer.store') :  route('consumer.update',[$data->id]) }}">
    @csrf
  
    <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">{{ $data->id == null ? 'Add' : 'Update' }} consumer</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="tname">GSTIN Number <span class='text-danger'>*</span></label>
                    <input type="text" class="form-control @error('gstin') is-invalid @enderror"  id="gstin" name="gstin" placeholder="Enter GSTIN Number" value="{{ old('gstin', $data->gstin) }}">
                    @error('gstin')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="gstn">Consumer Name <span class='text-danger'>*</span></label>
                    <input type="text" class="form-control @error('consumer_name') is-invalid @enderror" id="consumer_name" name="consumer_name" placeholder="Consumer Name" value="{{ old('consumer_name', $data->consumer_name) }}">
                    @error('consumer_name')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="contact_person">Contact Name <span class='text-danger'>*</span></label>
                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_name" name="contact_person" placeholder="Contact Name" value="{{ old('contact_person', $data->contact_person) }}">
                    @error('contact_person')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="contact_number">Contact Number <span class='text-danger'>*</span></label>
                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" placeholder="Contact Phone" value="{{ old('contact_number', $data->contact_number) }}">
                    @error('contact_number')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="address">Address <span class='text-danger'>*</span></label>
                    <textarea type="address" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Address">{{ old('address', $data->address) }}</textarea>
                    @error('address')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="pincode">Pin code <span class='text-danger'>*</span></label>
                    <input type="text" class="form-control @error('pincode') is-invalid @enderror"  id="pincode" name="pincode" placeholder="Pincode" value="{{ old('pincode', $data->pincode) }}">
                    @error('pincode')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="state_id">State <span class='text-danger'>*</span></label>
                    <select class="form-control select2 states" name="state_id">
                    @foreach($states as $s)
                      <option value="{{$s->getId()}}" @if(($data->getState()!=null) && $data->getState()->getId()==$s->getId()) selected='selected' @endif>{{ $s->getName() }}</option>
                    @endforeach
                    </select>
                    @error('state_id')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="{{route('consumer.index')}}" class="btn btn-secondary">Cancel</a>
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
    $('.states').select2();
});
</script>
@stop
