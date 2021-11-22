@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Update Vehicle</h1>
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
    @csrf
    {{ AutoVersion::asset('js/ewayvehicle/compiled.js') }}
    <div id="react"></div>
    @include('eway.search')
   
    </div> <!-- Col-4 end -->
<div class="col-4"></div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop



@section('adminlte_js')
<script>
var api_endpoint_ewaybill = '{{route('ajax.get.ewaybill',['id'=>'#'])}}';
</script>
<script src="{{ AutoVersion::asset('js/ewayvehicle/compiled.js') }}"></script>
@stop
