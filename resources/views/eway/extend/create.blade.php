@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')

    <form method="POST" action="{{ route('ewaybill.extend.send') }}">
        @csrf
       
        <div class="row">
            @if(session()->has('success'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{session()->get('success')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
            @if(session()->has('session_eway_extend'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    @foreach (session()->get('session_eway_extend') as $k => $item)
                        #{{$k}} : {{$item}} <br/>
                    @endforeach
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                {{session()->forget('session_eway_extend')}}
            @endif
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Extend Mulitple E-Way Bills 
                            @if(strpos(Request::url(),'extend/schedule'))
                            (Schedule time - 
                            @if(Session::has('schedule_datetime'))
                              {{ \Carbon\Carbon::createFromFormat('Y-m-d',Session::get('schedule_datetime'))->format('d/m/Y '.env('AUTO_SCHEDULER_TIME')) }}
                            @endif
                            )   
                        <input type="hidden" name="extend" value="1" />
                        @endif
                    </h3>
                        <div class="card-tools">
                            <button type="submit" @empty($ewayBills) disabled @endempty class="btn btn-danger" onclick="clearEwayIdsLocalStorage();">
                                SUBMIT
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="gstn">Reason <span class='text-danger'>*</span></label>
                                    <select class="form-control select2 reason" name="reason" id="extend_reason">
                                        @foreach($extend_reasons_list as $reason)
                                            <option value="{{$reason}}" selected>{{$reason}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="extension">Remark</label>
                                    <input type="text" class="form-control" name="extension"
                                        placeholder="Validity Extension" id="extend_remark" value="Others"/>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="consignment_status">Consignment Status</label>
                                    <select class="form-control select2 consignment_status" name="consignment_status" id="extend_consignment_status">
                                        <option value="T">In Transit</option>
                                        <option value="M" selected>In Movement</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Transit Type</label>
                                    <select class="form-control select2 transit_type" name="transit_type" id="extend_transit_type">
                                        <option value='road' selected>Road</option>
                                        <option value='warehouse'>Warehouse</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Current Place</label>
                                    <input type="text" class="form-control" name="current_place"
                                        placeholder="Current Place" id="current_place" />
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label>Current Pincode</label>
                                    <input type="text" class="form-control" name="current_pincode"
                                        placeholder="Current Pincode" id="current_pincode" />
                                </div>
                            </div>
                            <div class="col-2 pt-4">
                                <button type="button" @empty($ewayBills) disabled @endempty class="btn btn-primary"
                                    id="search_eway_bill"> <i class="fas fa-sync-alt"></i> APPLY ALL </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        @empty($ewayBills)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                        Please select E-way Bills in order to extend them. <a href="{{ route('ewaybill.index') }}">Click
                            here!</a>
                    </div>
                </div>
            </div>
        @else
            @php $count = 0; @endphp
            @foreach ($ewayBills as $item)
                @php ++$count; @endphp
                <div class="row r_{{ $item['eway_bill_number'] }}">
                    <div class="col-12">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title text-bold">(<span class="counter">{{ $count }}</span>) {{ $item['eway_bill_number'] }} - {{ $item['eway_bill_date'] }}</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool delete_ewaybill" data-id="{{ $item['eway_bill_number']}}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- /.card-tools -->
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <!-- Extra Hidden Fields -->
                                    <input type="hidden" class="form-control"
                                        name="data[{{ $item['eway_bill_number'] }}][eway_bill_number]"
                                        value="{{ $item['eway_bill_number'] }}" />
                                    <input type="hidden" class="form-control"
                                        name="data[{{ $item['eway_bill_number'] }}][userGstin]"
                                        value="{{ $item['gstin_of_consignor'] }}" />
                                        
                                        <input type="hidden" class="form-control"
                                        name="data[{{ $item['eway_bill_number'] }}][state_of_consignor]"
                                        value="{{ $item['state_of_consignor'] }}" />

                                        <input type="hidden" class="form-control"
                                        name="data[{{ $item['eway_bill_number'] }}][place_of_consignor]"
                                        value="{{ $item['place_of_consignor'] }}" />

                                    
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="from_place">Current Place</label>
                                            <input type="text" class="form-control current_place"
                                                name="data[{{ $item['eway_bill_number'] }}][from_place]" placeholder=""
                                                value="{{ $item['place_of_consignee'] }}" required />
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="gstn">Current State</label>
                                            <select class="form-control select2 from_state"
                                                name="data[{{ $item['eway_bill_number'] }}][from_state]" required>

                                                @foreach($state_list as $state)
                                                    @if($item['state_of_supply'] == $state)
                                                        <option value="{{$state}}" selected>{{$state}}</option>
                                                    @else
                                                        <option value="{{$state}}">{{$state}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="from_pincode">From Pincode</label>
                                            <input type="text" class="form-control current_pincode"
                                                name="data[{{ $item['eway_bill_number'] }}][from_pincode]" placeholder=""
                                                value="{{ $item['pincode_of_consignor'] }}" />
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="remaining_distance">Remaining Distance</label>
                                            @php $d = (int)$item['transportation_distance']-1; @endphp
                                            <input type="text" class="form-control"
                                                name="data[{{ $item['eway_bill_number'] }}][remaining_distance]" placeholder=""
                                                value="{{$d}}" required />
                                        </div>
                                    </div>
                                    <div class='col-2'>
                                        <div class='form-group'><label for='vehicle_number'>Vehicle Number</label><input
                                                type='text' class='form-control'
                                                name="data[{{ $item['eway_bill_number'] }}][vehicle_number]"
                                                placeholder='Vehicle Number' value="{{ $item['vehicle_number'] }}" required /></div>
                                    </div>
                                    <div class='col-2'>
                                        <div class='form-group'>
                                            <label for='gstn'>Reason</label>
                                            <select class='form-control select2 ewaybill_reason'
                                                name="data[{{ $item['eway_bill_number'] }}][extend_validity_reason]" required>
                                                @foreach($extend_reasons_list as $reason)
                                                    <option value="{{$reason}}">{{$reason}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-2'>
                                        <div class='form-group'><label for='remarks'>Remarks</label>
                                            <input type='text' class='form-control ewaybill_remarks'
                                                name="data[{{ $item['eway_bill_number'] }}][extend_remarks]" placeholder='Remarks'
                                                value="Remarks" required />
                                        </div>
                                    </div>
                                    <div class='col-2'>
                                        <div class='form-group'>
                                            <label for='gstn'>Consignment Status</label>
                                            <select class='form-control select2 ewaybill_consignment_status'
                                                name="data[{{ $item['eway_bill_number'] }}][consignment_status]" data-ewaybill="{{ $item['eway_bill_number'] }}" required>
                                                <option value='T' >In Transit</option>
                                                <option value='M' selected>In Movement</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-2'>
                                        <div class='form-group'>
                                            <label for='gstn'>Transit Type</label>
                                            <select class='form-control transit_type select2 ewaybill_transit_type'
                                                name="data[{{ $item['eway_bill_number'] }}][mode_of_transport]" required>
                                                <option value='road' selected>Road</option>
                                                <option value='warehouse' >Warehouse</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class='col-2'>
                                        <div class='form-group'><label for='transport_doc_date'>Transport Doc Date
                                            </label><input type='date' class='form-control'
                                                name="data[{{ $item['eway_bill_number'] }}][transporter_document_date]"
                                                placeholder='Transport Doc Date'
                                                value="{{ \App\Libraries\Helpers\Helper::dateFormatUnix($item['transporter_document_date']) }}"  />
                                        </div>
                                    </div>
                                    <div class='col-2 d-none'>
                                        <div class='form-group'><label for='transport_doc_no'>Transport Doc No.</label><input
                                                type='text' class='form-control'
                                                name="data[{{ $item['eway_bill_number'] }}][transporter_document_number]"
                                                placeholder='Transport Doc No.'
                                                value="{{ $item['transporter_document_number'] }}" /></div>
                                    </div>
                                    <div class="col-2 d-none">
                                        <div class="form-group">
                                            <label for="address_line1">Address Line 1</label>
                                            <input type="text" class="form-control address"
                                                name="data[{{ $item['eway_bill_number'] }}][address_line1]"
                                                placeholder="Address Line 1" value="" data-xvalue="{{ $item['address1_of_consignee'] }}" />
                                        </div>
                                    </div>

                                    <div class="col-2 d-none">
                                        <div class="form-group">
                                            <label for="address_line2">Address Line 2</label>
                                            <input type="text" class="form-control address"
                                                name="data[{{ $item['eway_bill_number'] }}][address_line2]"
                                                placeholder="Address Line 2" value="" data-xvalue="{{ $item['address2_of_consignee'] }}" />
                                        </div>
                                    </div>
                                    <div class="col-2 d-none">
                                        <div class="form-group">
                                            <label for="address_line3">Address Line 3</label>
                                            <input type="text" class="form-control address"
                                                name="data[{{ $item['eway_bill_number'] }}][address_line3]"
                                                placeholder="Address Line 3" value="" data-xvalue="{{ $item['eway_bill_number'] }}" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endempty
    </form>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop



@section('adminlte_js')
    <script>
        $(document).ready(function() {
            $('.reason').select2();
            // $('.transit_type').select2();
            $('.from_state').select2();
        });
        $(document).ready(function() {
            $('.address').closest('.col-2').addClass('d-none');
            // $('.ewaybill_consignment_status').select2();
            $('.ewaybill_consignment_status').change(function(e){
                let current_row = $('.r_'+$(this).data('ewaybill'));
                if($(this).val()=='M'){
                    current_row.find('.address').val('');
                    current_row.find('.transit_type').val('road');
                    $('.address').closest('.col-2').addClass('d-none');
                }else{
                    current_row.find('.col-2').removeClass('d-none');
                    current_row.find('.transit_type').val('warehouse');
                    current_row.find('.address').each(function(e){
                        $(this).val($(this).data('xvalue'));
                    });
                    // current_row.find('.transit_type').select2().select2('val','Warehouse');
                    // current_row.find('.transit_type').select2('data', { text: "warehouse"});
                    
                }

            });

            $("#search_eway_bill").click(function(e)
            {   
                if (confirm('Are You Sure?')) {
                    var extend_reason_val = $("#extend_reason").val();
                    var extend_consignment_status = $("#extend_consignment_status").val();
                    var extend_transit_type = $("#extend_transit_type").val();
                    var extend_remark = $("#extend_remark").val();
                    var current_place = $("#current_place").val();
                    var current_pincode = $("#current_pincode").val();
                    $(".ewaybill_reason").val(extend_reason_val);
                    $(".ewaybill_consignment_status").val(extend_consignment_status);
                    $(".ewaybill_transit_type").val(extend_transit_type);
                    $(".ewaybill_remarks").val(extend_remark);
                    if(current_place!='')
                        $(".current_place").val(current_place);
                    if(current_pincode!='')
                        $(".current_pincode").val(current_pincode);
                    $(".ewaybill_consignment_status").trigger('change');
                }

            });

            $(".delete_ewaybill").click(function(e)
            {
                if (!confirm('Are You Sure?'))
                    return false; 

                var bill_id = $(this).attr('data-id');
                $(".r_"+bill_id).remove();
                
                addeWayIdLocalStorate(bill_id,false);

                // Reset Counters
                let i = 1;
                $('.counter').each(function(e){
                    $(this).text(i);
                    i++;
                })

                let add_to_session_url = '{{ route('ajax.ewaybill.collectids', ['#id','#action']) }}';
                $.ajax({
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: (add_to_session_url.replace('#id',bill_id)).replace('#action',false),
                    data: {}
                })
                .done(function( msg ) {
                });
            });
        });
    </script>
@stop
