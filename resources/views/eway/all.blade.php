@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
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
    <div class="row">
        <div class="col-md-12">
            <div class="card p-2">
                <div class="card_header filters">
                    <div class="row">
                    <div class="pull-left col-10">
                    <!-- <label>Document Number </label>  -->
                    <!-- <input type="text" class="filter" name="document_number" id="document_number" />  -->
                    {{-- <label>GSTIN</label>
                    <input type="text" class="filter" name="gstin_of_consignor" id="gstin_of_consignor" />  --}}
                    <label>Valid Till Date </label>
                    <input type="date" class="filter" value="" format="mm/dd/yyyy" name="document_date" id="document_date" /> 
                    <input type="button" value="Filter" name="filter" id="filter">
                    </div>
                    
                    </div>
                    <hr/>
                </div>
                <div class="card_body">
                    <table id="list" class="display pretty" style="width:100%">
                        <thead>
                            <tr>
                                <th>Eway Bill #</th>
                                <th>Consignor Details</th>
                                <th>Consignee Details</th>
                                <th>Bill Date</th>
                                <th>Vehicle Number</th>
                                <th>Valid Till </th>
                            </tr>
                        </thead>
                    
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop


@section('adminlte_js')
<script>

   
$(document).ready(function() {
var oTable = $('#list').DataTable( {
        "dom": 'frtip',
        "processing": true,
        "serverSide": true,
        "stateSave": false,
        "paging":   false,
        "searching": true,
        "ajax": {
            url: '{{route('ewaybill.all')}}',
            data: function (d) {
                d.document_number = $('#document_number').val(),
                d.gstin_of_consignor = $('#gstin_of_consignor').val(),
                d.document_date = $('#document_date').val();
            },
            error: function (xhr, error, code)
            {
                $(document).Toasts('create', {
                    title:'ERROR FOUND',
                    autohide: true,
                    delay: 3000,
                    class: 'bg-danger',
                    body: xhr.responseText
                    })
                $('#list_processing').hide();
            }
        },
        "columns": [
            { 
                "data": "eway_bill_number",
                "render" :function(data,type,row){
                    if (type === 'display') {
                      return prepareEwayHTML(data,row);
                    }
                    return data; 
                }
            },
            { 
                "data": "legal_name_of_consignor",
                "render": function(data,type,row){
                    if (type === 'display') {
                      return prepareConsignorHTML(data,row);
                    }
                    return data;
                }
            },
            { 
                "data": "legal_name_of_consignee",
                "render": function(data,type,row){
                    if (type === 'display') {
                      return prepareConsigneeHTML(data,row);
                    }
                    return data;
                }
            },
               { "data": "eway_bill_date" },
            { "data": "vehicle_number" }, 
            { "data": "eway_bill_valid_date" }, 
            { "data": "address1_of_consignor","visible": false }, 
            { "data": "place_of_consignor","visible": false }, 
            { "data": "pincode_of_consignor","visible": false }, 
            { "data": "gstin_of_consignee","visible": false }, 
            { "data": "legal_name_of_consignee","visible": false }, 
            { "data": "address2_of_consignee","visible": false }, 
            { "data": "place_of_consignee","visible": false }, 
            { "data": "pincode_of_consignee","visible": false }, 
            { "data": "state_of_supply","visible": false }, 
            { "data": "document_number","visible": false }, 
            
        ]
    } );

function prepareConsigneeHTML(data,row){
    let html = '<b>'+row['legal_name_of_consignee']+'</b>';
    html += '<br>GSTIN: '+row['gstin_of_consignee'];
    html += '<br>'+row['address1_of_consignee'];
    html += '<br>'+row['address2_of_consignee'];
    html += '<br>'+row['pincode_of_consignee'];
    html += ', '+row['place_of_consignee'];
    html += ', '+row['state_of_supply'];
    
    return html;
}

function prepareConsignorHTML(data,row){
    let html = '<b>'+row['legal_name_of_consignor']+'</b>';
    html += '<br>GSTIN: '+row['gstin_of_consignor'];
    html += '<br>'+row['address1_of_consignor'];
    html += '<br>'+row['address2_of_consignor'];
    html += '<br>'+row['pincode_of_consignor'];
    html += ', '+row['place_of_consignor'];
    html += ', '+row['state_of_consignor'];
    
    return html;
}

function prepareEwayHTML(data,row){
    let url = '{{ route('ewaybill.detail', ['#e','#g']) }}';
    let ewaybill_detail_url = url.replace('#e',row['eway_bill_number']);
    ewaybill_detail_url = ewaybill_detail_url.replace('#g',row['gstin_of_consignor']);

    let html = '<b><a href="'+ewaybill_detail_url+'" target="_blank">'+row['eway_bill_number']+'</a></b>';
    html += '<br>'+row['supply_type'];
    html += ' ('+row['sub_supply_type']+')';
    html += '<br>'+row['document_type'];
    html += '<br>'+row['document_date'];
    
    return html;
}

function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}

$('#filter').click(function(e) {
    oTable.search('').columns().search('').draw();
    oTable.draw();
    e.preventDefault();
});

});
</script>

@stop