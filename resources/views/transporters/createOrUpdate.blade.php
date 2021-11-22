@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Transporter</h1>
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
<form method="POST" action="{{ $data->id == null ? route('transporter.store') :  route('transporter.update',[$data->id]) }}">
    @csrf
  
    <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">{{ $data->id == null ? 'Add' : 'Update' }} Transporter</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form>
                <div class="card-body">
                  <div class="form-group">
                    <label for="tname">Name <span class='text-danger'>*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"  id="name" name="name" placeholder="Enter Name" value="{{ old('name', $data->name) }}">
                    @error('name')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="gstn">GSTIN <span class='text-danger'>*</span></label>
                    <input type="text" class="form-control" id="gstin" name="gstin" placeholder="GSTIN" value="{{ old('gstin', $data->gstin) }}">
                    @error('gstin')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="contact_person">Contact Person <span class='text-danger'>*</span></label>
                    <input type="text" class="form-control" id="contact_name" name="contact_person" placeholder="Contact Name" value="{{ old('contact_person', $data->contact_person) }}">
                    @error('contact_person')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="contact_number">Contact Number <span class='text-danger'>*</span></label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Contact Phone" value="{{ old('contact_number', $data->contact_number) }}">
                    @error('contact_number')
                      <span class="text-danger">{{ $message }}</span> 
                    @enderror
                  </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="{{route('transporter.index')}}" class="btn btn-secondary">Cancel</a>
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

@stop
