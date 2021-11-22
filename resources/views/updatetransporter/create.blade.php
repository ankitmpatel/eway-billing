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
                        <h3 class="card-title">Update Transporters
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
                                    <label for="gstn">Transporter <span class='text-danger'>*</span></label>
                                    <select class="form-control select2 transporters" name="transporters" id="extend_transporters">
                                       
                                    </select>
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
                                                                      
                                        
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label>Transporter</label>
                                                <select class="form-control select2 transporters" name="transporters[]" data-ewayid="{{ $item['eway_bill_number'] }}">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label>Transporter Id</label><br/>
                                                <input type="text" class="form-control transId" disabled value="2342425" name="transId[]" id="trans_{{ $item['eway_bill_number'] }}" />
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
            $('.transporters').select2({
                placeholder: 'Select an item',
                ajax: {
                    url: '{{route("transporter.get.ajax")}}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.gstin
                            }
                        })
                    };
                    },
                },
                templateSelection: function (data, container) {
                    $(data.element).closest('.row').find('.transId').val(data.id);
                    return data.text;
                }
                });
        });
        $(document).ready(function() {
            $('#js-data-example-ajax').select2();
            $("#search_eway_bill").click(function(e)
            {   
                if (confirm('Are You Sure?')) {
                    console.log($(this).closest('.row').find('.transporters').val());
                    // $('.transporters').select2({
                    //     data:[{id:0,text:'hemlo'}]
                    // })
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
