@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to CargoEscort-eWay Bill Management System.</p>
    
    <table id="example" class="display pretty" style="width:100%">
        <thead>
            <tr>
            <th>First name</th>
                <th>First name</th>
                <th>Last name</th>
                
            </tr>
        </thead>
        <tfoot>
            <tr>
            <th>First name</th>
                <th>First name</th>
                <th>Last name</th>
                
            </tr>
        </tfoot>
    </table>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop



@section('adminlte_js')
<script>
var api_endpoint_users = '{{route('get.users')}}';
$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": api_endpoint_users,
        "columns": [
            { "data": "name" },
            { "data": "email" },
            { "data": "id" },
           
        ]
    } );
} );
</script>
@stop
