@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
    <div class="error-page">
        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! We didn't get response.</h3>
          <small>Sometimes It takes time to get response from MasterIndia API.</small>
          <br/><br/><br/>
          <a class="btn btn-primary" href="{{route('ewaybill.index')}}" role="button">Go to Dashboard</a>
        </div>
    </div>    

@stop