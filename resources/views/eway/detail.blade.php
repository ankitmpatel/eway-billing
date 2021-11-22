@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop
@section('content')
<div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i><strong> {{$billDetails['eway_bill_number']}}</strong><br>
                    
                    <div style="font-size:18px">
                      <small>TYPE: <strong>E-WAY BILL</strong> </small> &nbsp;
                      <small>STATUS: <strong>ACTIVE</strong> </small> &nbsp;
                      <small>CREATED: <strong>{{$billDetails['eway_bill_date']}}</strong> </small> &nbsp;
                      <small>Valid TILL: <strong>{{$billDetails['eway_bill_valid_date']}}</strong></small>
                    </div>
                    <hr/>
                    
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  <h4><strong>Transaction Details</strong></h4>
                    <p>TRANSACTION TYPE <br>
                    <strong>{{$billDetails['supply_type'] ?? "-"}}</strong>
                  </p>

                  <p>SUB TYPE<br>
                    <strong>{{$billDetails['sub_supply_type'] ?? "-"}}</strong>
                  </p>

                  <p>DOCUMENT TYPE<br>
                    <strong>Bill Of Supply</strong><br>
                    <span>DOCUMENT NUMBER</span><br>
                    <strong>{{$billDetails['document_number'] ?? "-"}}</strong><br>

                    <span>DOCUMENT DATE</span><br>
                    <strong>{{$billDetails['document_date'] ?? "-"}}</strong><br>

                    <span>TRANSACTION TYPE</span><br>
                    <strong>Bill To - Ship To</strong>
                  </p>
                  
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <h4><strong>Bill From</strong></h4>
                  <p>LEGAL NAME & GSTIN<br>
                    <strong>{{$billDetails['legal_name_of_consignor'] ?? "-"}} - {{$billDetails['gstin_of_consignor'] ?? "-"}}</strong>
                  </p>

                  <p>STATE<br>
                    <strong>{{$billDetails['state_of_consignor'] ?? "-"}} </strong>
                  </p>

                  <span><strong>Dispatch From</strong></span>

                  <address>
                    {{$billDetails['address1_of_consignor'].',' ?? "-"}} <br>
                    {{$billDetails['address2_of_consignor'].',' ?? "-"}} <br>
                    {{$billDetails['place_of_consignor'].',' ?? "-"}} <br>
                    {{$billDetails['pincode_of_consignor'].',' ?? "-"}} <br>
                    {{$billDetails['state_of_consignor'].'.' ?? "-"}}
                    
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <h4><strong>Bill To</strong></h4>
                  <p>LEGAL NAME & GSTIN<br>
                    <strong>{{$billDetails['legal_name_of_consignee'] ?? "-"}} - {{$billDetails['gstin_of_consignee'] ?? "-"}}</strong>
                  </p>

                  <p>STATE<br>
                    <strong>{{$billDetails['state_of_supply'] ?? "-"}} </strong>
                  </p>

                  <strong>Dispatch To</strong><br>

                  <address>
                    {{$billDetails['address1_of_consignee'].',' ?? "-"}} <br>
                    {{$billDetails['address2_of_consignee'].',' ?? "-"}} <br>
                    {{$billDetails['place_of_consignee'].',' ?? "-"}} <br>
                    {{$billDetails['pincode_of_consignee'].',' ?? "-"}} <br>
                    {{$billDetails['state_of_supply'].'.' ?? "-"}}
                    
                  </address>
              <hr>

                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              <!-- Table row -->
              <div class="row">
                <div class="col-12">
                  <p>ALERT<br>
                  <strong>The Distance between the given pincodes are not available in the system</strong>
              <hr>

                </div>  
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-4 invoice-col">
                  <h4><strong>Transporter Details (Part B)</strong></h4>
                  <p>TRANSPORTER NAME<br>
                    <strong>{{$billDetails['transporter_name'] ?? "-"}}</strong>
                  </p>

                  <p>TRANSPORTER GSTIN/ID<br>
                    <strong>{{$billDetails['transporter_id'] ?? "-"}}</strong>
                  </p>

                  <p>TRANSPORTER DOCUMENT DATE<br>
                    <strong>{{$billDetails['transporter_document_date'] ?? "-"}}</strong>
                  </p>

                </div>  

                <div class="col-4 invoice-col">
                  <h4><strong><br></strong></h4>
                  <p>TRANSPORTION MODE<br>
                    <strong>{{$billDetails['transportation_mode'] ?? "-"}}</strong>
                  </p>

                  <p>TRANSPORTION DISTANCE<br>
                    <strong>{{$billDetails['transportation_distance'] ?? "-"}}</strong>
                  </p>

                  <p>TRANSPORTION DISTANCE<br>
                    <strong>{{$billDetails['transportation_distance'] ?? "-"}}</strong>
                  </p>
                </div>

                <div class="col-4 invoice-col">
                  <h4><strong><br></strong></h4>
                  <p>VEHICLE NUMBER<br>
                    <strong>{{$billDetails['vehicle_number'] ?? "-"}}</strong>
                  </p>

                  <p>VEHICLE TYPE<br>
                    <strong>{{$billDetails['vehicle_type'] ?? "-"}}</strong>
                  </p>

                  <p>LOCATION CODE<br>
                    <strong>{{$billDetails['location_code'] ?? "-"}}</strong>
                  </p>

                </div>  

              </div>
              <!-- /.row -->
              <hr>
              <div class="col-12 table-responsive">
                  <h4><strong>ITEMS</strong></h4>
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Qty</th>
                      <th>Product</th>
                      <th>HSN Code</th>
                      <th>Product Description</th>
                      <th>Unit Of Product</th>
                      <th>CGST Rate</th>
                      <th>SGST Rate</th>
                      <th>IGST Rate</th>
                      <th>Taxable Amount</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if(!empty($billDetails['itemList']))
                      @foreach($billDetails['itemList'] as $itemList)
                        <tr>
                          <td>{{$itemList['quantity'] ?? "-"}}</td>
                          <td>{{$itemList['product_name'] ?? "-"}}</td>
                          <td>{{$itemList['hsn_code'] ?? "-"}}</td>
                          <td>{{$itemList['product_description'] ?? "-"}}</td>
                          <td>{{$itemList['unit_of_product'] ?? "-"}}</td>
                          <td>{{$itemList['cgst_rate'] ?? "-"}}</td>
                          <td>{{$itemList['sgst_rate'] ?? "-"}}</td>
                          <td>{{$itemList['igst_rate'] ?? "-"}}</td>
                          <td>{{$itemList['taxable_amount'] ?? "-"}}</td>
                      </tr>
                      @endforeach
                    @endif
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->



              <div class="row">
              
                <!-- /.col -->
                <div class="col-6 offset-6">
                  <p class="lead"></p>
                  <div class="table-responsive">
                    <table class="table">
                      <tbody><tr>
                        <th style="width:50%">Taxable Amount:</th>
                        <td>Rs.{{$billDetails['taxable_amount'] ?? "-"}}</td>
                      </tr>
                      <tr>
                        <th>CGST</th>
                        <td>Rs.{{$billDetails['cgst_amount'] ?? "-"}}</td>
                      </tr>
                      <tr>
                        <th>SGST:</th>
                        <td>Rs.{{$billDetails['sgst_amount'] ?? "-"}}</td>
                      </tr>
                      <tr>
                        <th>Total:</th>
                        <td>Rs.{{$billDetails['taxable_amount'] ?? "-"}}</td>
                      </tr>
                    </tbody></table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              {{-- <div class="row no-print">
                <div class="col-12">
                  <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                  <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                    Payment
                  </button>
                  <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                  </button>
                </div>
              </div> --}}
            </div>

            @stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop