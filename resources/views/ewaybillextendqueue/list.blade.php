@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>E-Way Bills Queue</h1>
@stop

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('success') }}
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
                        <div class="pull-left col-5">
                            <label>Filters &nbsp;</label>
                            <!-- <input type="text" class="filter" name="document_number" id="document_number" />  -->

                            <a data-idw="" javascript="void(0)" class="btn btn-primary text-white searchbtn"> <i
                                    class="fas fa-bullseye"></i> All</a>
                            <a data-idw="failure" javascript="void(0)" class="btn btn-danger text-white searchbtn"> <i
                                    class="fas fa-exclamation-circle"></i> Failure</a>
                            <a data-idw="success" class="btn btn-success text-white searchbtn"><i
                                    class="fas fa-check-circle"></i> Success</a>
                            <a data-idw="pending" class="btn btn-warning text-white searchbtn"> <i
                                    class="fas fa-layer-group"></i> Pending</a>

                        </div>
                        <div class="pull-left col-5">
                            <select name="batchdatetime" id="batchdatetime" class="form-control searchtime col-6">
                                <option value="">Select All</option>
                                @php $i=0; @endphp
                                @foreach ($batches as $item)
                                    <option value="{{ $item }}" @if ($i == 0) selected @endif>{{ $item }}</option>
                                    @php ++$i; @endphp;
                                @endforeach
                            </select>
                        </div>
                        <div class="pull-right text-right col-2">
                            <i class="fas fa-circle-notch d-none loader fa-spin"></i> <input type="button" id="extend"
                                class="btn btn-primary" name="extend" value="Extend">
                        </div>
                    </div>
                    <hr />
                </div>
                <div class="card_body">
                    <table id="list" class="display pretty rowclick" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" name="select_all" value="1" id="select-all">
                                </th>
                                <th>Status</th>
                                <th>Eway Bill #</th>
                                <th>Response</th>
                                <th>Batch Id</th>
                                <th>Batch Date time</th>
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
            var oTable = $('#list').DataTable({
                "dom": 'frtip',
                "processing": true,
                "serverSide": true,
                "stateSave": false,
                "paging": true,
                "searching": true,
                "ajax": {
                    url: '{{ route('ewaybillextendqueue.index') }}',
                    data: function(d) {
                        d.document_number = $('#document_number').val(),
                            d.gstin_of_consignor = $('#gstin_of_consignor').val(),
                            d.document_date = $('#document_date').val();
                    },
                    error: function(xhr, error, code) {
                        $(document).Toasts('create', {
                            title: 'ERROR FOUND',
                            autohide: true,
                            delay: 3000,
                            class: 'bg-danger',
                            body: xhr.responseText
                        })
                        $('#list_processing').hide();
                    }
                },
                "columns": [{
                        "data": "eway_bill_number",
                        "render": function(data, type, row) {
                            if (type === 'display') {
                                return prepareCheckBoxHTML(data, row);
                            }
                            return data;
                        },
                        "bSortable": false,
                        "className": "text-center cpointer"
                    },
                    {
                        "data": "status",
                        "render": function(data, type, row) {
                            if (type === 'display') {
                                return prepareStatusHTML(data, row);
                            }
                            return data;
                        }
                    },
                    {
                        "data": "eway_bill_number",
                        "render": function(data, type, row) {
                            if (type === 'display') {
                                return prepareEwayHTML(data, row);
                            }
                            return data;
                        }
                    },
                    {
                        "data": "response",
                        "render": function(data, type, row) {
                            if (type === 'display') {
                                return prepareResponseHTML(data, row);
                            }
                            return data;
                        }
                    },
                    {
                        "data": "batch_id"
                    },
                    {
                        "data": "batch_datetime"
                    },


                ],
                "order": [
                    [4, "desc"]
                ]
            });


            function prepareStatusHTML(data, row) {

                let html = '';
                if (row['status'] == 'pending') {
                    html += '<small class="badge badge-warning"><i class="fas fa-layer-group"></i> Pending</small>';
                } else if (row['status'] == 'success') {
                    html +=
                        '<small class="badge badge-success"><i class="fas fa-check-circle"></i> Success</small>';
                } else {
                    html +=
                        '<small class="badge badge-danger"><i class="fas fa-exclamation-circle"></i> Failure</small>';
                }

                return html;
            }

            function prepareEwayHTML(data, row) {
                let request = decodeHTMLEntities(row['request']);
                let objRequest = jQuery.parseJSON(request);
                let url = '{{ route('ewaybill.detail', ['#e', '#g']) }}';
                let ewaybill_detail_url = url.replace('#e', objRequest['eway_bill_number']);
                ewaybill_detail_url = ewaybill_detail_url.replace('#g', objRequest['userGstin']);

                let html = '<b><a href="' + ewaybill_detail_url + '" target="_blank">' + objRequest['eway_bill_number'] +
                    '</a></b>';
                return html;
            }

            function prepareResponseHTML(data, row) {

                let html = '';
                try {
                    let response = decodeHTMLEntities(row['response']);
                    let objResponse = jQuery.parseJSON(response);
                    if (objResponse['code'] == 200) {
                        html += objResponse['message']['ewayBillNo'] + ' valid upto ' + objResponse['message'][
                            'validUpto'
                        ];
                    } else {
                        html += objResponse['message'];
                    }
                    console.log(objResponse);
                    return html;
                } catch (err) {
                    return 'N/A';
                }

            }

            function decodeHTMLEntities(text) {
                var entities = [
                    ['amp', '&'],
                    ['apos', '\''],
                    ['#x27', '\''],
                    ['#x2F', '/'],
                    ['#39', '\''],
                    ['#47', '/'],
                    ['lt', '<'],
                    ['gt', '>'],
                    ['nbsp', ' '],
                    ['quot', '"']
                ];

                for (var i = 0, max = entities.length; i < max; ++i)
                    text = text.replace(new RegExp('&' + entities[i][0] + ';', 'g'), entities[i][1]);

                return text;
            }

            function prepareCheckBoxHTML(data, row) {
                let ss = $.parseJSON(getEwayIdsLocalStorage());  
                
                let select_ele = '';
                if(ss!=null && ss.includes(data.toString())){
                    select_ele = 'checked';
                }

                let html = '';
                if (row['status'] == 'failure') {
                    let a = decodeHTMLEntities(row['original_request']);
                    html = '<input type="checkbox" id="eid_' + data + '_' + row['batch_id'] +
                        '" name="eway_id[]" ' + select_ele + ' class="eway_id zindex-99" value="' + data + '"/>';
                    html += '<input type="hidden" name="eway_row_data' + data + '" id="eway_row_data_' + data +
                        '" class="eway_id" value="' + encodeURIComponent(JSON.stringify(JSON.parse(a))) + '"/>';
                }

                return html;
            }

            $('.searchbtn').click(function(e) {
                oTable.columns(1).search($(this).data('idw'));
                oTable.columns(5).search($('#batchdatetime').val());
                oTable.draw();
                e.preventDefault();
            });

            oTable.columns(5).search($('#batchdatetime').val());
            oTable.draw();

            $('.searchtime').change(function(e) {
                oTable.columns(5).search($('#batchdatetime').val());
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
                    let e = '#eid_' + d['eway_bill_number'] + '_' + d['batch_id'];
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
                console.log($('#select-all').prop('checked'));
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

        });

    </script>

@stop
