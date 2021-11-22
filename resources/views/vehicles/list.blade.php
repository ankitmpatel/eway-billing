@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Vehicles</h1>
@stop

@section('content')
@if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{session()->get('success')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    <table id="list" class="display pretty" style="width:100%">
        <thead>
            <tr>
            <th>Vehicle Number</th>
                <th>Transporter</th>
                <th>Action </th>
            </tr>
        </thead>
       
    </table>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop



@section('adminlte_js')
<script>

   
$(document).ready(function() {
    $('#list').DataTable( {
        "dom": 'lf<"toolbar">rtip',
        "processing": false,
        "serverSide": true,
        "stateSave": true,
        "ajax": '{{route('vehicle.index')}}',
        "columns": [
            { "data": "vehicle_number" },
            { "data": "transporter" },
            {
                data: "id",
                className: "center",
                'render': function (id) {
                            let edit_base_url = '{{ route('vehicle.edit', '#') }}';
                            let edit_url = edit_base_url.replace('#',id);
                            let delete_base_url = '{{ route('vehicle.destroy', '#') }}';
                            let delete_url = delete_base_url.replace('#',id);
                            return '<a href="'+edit_url+'">Edit</a> / <a href="'+delete_url+'" class="confirmation" >Delete</a>';
                          }
            }
           
        ]
    } );
    $("div.toolbar").html('<span class="pl-3"> <a href="{{route('vehicle.create')}}" class="btn btn-primary"> <i class="fa fa-plus"> </i> Add</a></span>');
} );
$('#list').on('click','a.confirmation', function () {
        return confirm('Are you sure?');
});
</script>
@stop
