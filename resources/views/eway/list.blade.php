@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Extend E-Way Bills</h1>
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
                    <label class='d-none'>GSTIN</label>
                    <input type="text" class="filter d-none" name="gstin_of_consignor" id="gstin_of_consignor" /> 
                    <label class='d-none'>Document Date </label>
                    <input type="hidden" id="todaydate" value="{{\Carbon\Carbon::today(App\Libraries\Helpers\Helper::getTimeZone())->format('Y-m-d')}}" /> 
                    <input type="hidden" id="tomorrowdate" value="{{\Carbon\Carbon::tomorrow(App\Libraries\Helpers\Helper::getTimeZone())->format('Y-m-d')}}" /> 
                    <input type="date" class="filter d-none" value="" format="mm/dd/yyyy" name="document_date" id="document_date" /> 
                    <a href="javascript:void(0)" class="btn btn-danger"  name="texpire" id="texpire"><i class="far fa-calendar-times"> </i> Today's Expiring ({{$counter['today']}})</a>
                    <a href="javascript:void(0)" class="btn btn-success"  name="tmexpire" id="tmexpire"><i class="fas fa-calendar-day"> </i> Tomorrows's Expiring ({{$counter['tomorrow']}})</a>
                    <input type="button" value="Filter" name="filter" id="filter" class='d-none'>
                    </div>
                    <div class="pull-right text-right col-2">
                    {{-- <a href="{{route('ewaybill.extend')}}" class="btn btn-primary">Extend</a> --}}
                    <i class="fas fa-circle-notch d-none loader fa-spin"></i> <input type="button" id="extend"
                    class="btn btn-primary" name="extend" value="Extend">
                    </div>
                    </div>
                    <hr/>
                </div>
                <div class="card_body">
                    <table id="list" class="display pretty rowclick" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" name="select_all" value="1" id="select-all">
                                </th>
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
            url: '{{route('ewaybill.index')}}',
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
                      return prepareCheckBoxHTML(data,row);
                    }
                    return data; 
                },
                "bSortable": false,
                "className": "text-center cpointer"
            },
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
            
        ],
        "order": [
                    [6, "desc"]
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

function prepareCheckBoxHTML(data,row){
    let ss = $.parseJSON(getEwayIdsLocalStorage());  
    let select_ele = '';
    if(ss!=null && ss.includes(data.toString()))
        select_ele = 'checked';
    
    let html = '<input type="checkbox" id="eid_'+data+'" name="eway_id" '+select_ele+' class="eway_id" value="'+data+'"/>';
    html += '<input type="hidden" name="eway_row_data'+data+'" id="eway_row_data_'+data+'" class="eway_id" value="'+encodeURIComponent(JSON.stringify(row))+'"/>';
    
    return html;
}

$('#filter').click(function(e) {
    oTable.search('').columns().search('').draw();
    oTable.draw();
    e.preventDefault();
});


// Collect EwayBill Ids
let add_to_session_url = '{{ route('ajax.ewaybill.collectids.multiple') }}';
let extend_url = '{{ route('ewaybill.extend') }}';

// Row click
$('#list tbody').on('click', 'tr', function() {
    if (event.target.type !== 'checkbox') {
        let d = oTable.row(this).data();
        let e = '#eid_' + d['eway_bill_number'];
        if ($(e).prop('checked'))
            $(e).prop('checked', false);
        else
            $(e).prop('checked', true);
        
         addeWayIdLocalStorate($(e).val(),$(e).prop('checked'));

    }

});

// Select All
$('#select-all').on('click', function() {
    var rows = oTable.rows({
        'search': 'applied'
    }).nodes();
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
    rows.each(function(e){
        let ele = $(e).find('input[type="checkbox"]');
        addeWayIdLocalStorate($(ele).val(),$('#select-all').prop('checked'));
    });
    
});

$('#list tbody').on('change', 'input[type="checkbox"]', function() {
    if (!this.checked) {
        var el = $('#select-all').get(0);
        if (el && el.checked && ('indeterminate' in el)) {
            el.indeterminate = true;
            addeWayIdLocalStorate($(this).val(),el.checked);
        }
    }
    
});

// Handle form submission event
$('#extend').on('click', function(e) {
    $('.loader').removeClass('d-none');
    let eway_data = [];
    oTable.$('input[type="checkbox"]').each(function() {
        if (this.checked) {
            eway_data.push($('#eway_row_data_' + $(this).val()).val());
        }
    });
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: add_to_session_url,
            data: {
                'eway_data': eway_data
            }
        })
        .done(function(msg) {
            if (msg) {
                window.location = extend_url;
            } else {
                $('.loader').addClass('d-none');
                $(document).Toasts('create', {
                    title: 'ERROR FOUND',
                    autohide: true,
                    delay: 3000,
                    class: 'bg-danger',
                    body: 'Please select at least one eWayBill.'
                })
            }
        });
    e.preventDefault();
});


// Today's expiring
$('#texpire').click(function(e){
    $('#document_date').val($('#todaydate').val());
    $('#filter').trigger('click');
});

// Tomorrow's expiring
$('#tmexpire').click(function(e){
    $('#document_date').val($('#tomorrowdate').val());
    $('#filter').trigger('click');
});

});
</script>

@stop