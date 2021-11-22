@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Auto Schedule</h1>
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
    <form method="POST" action="{{ route('ewaybillextendqueue.autoschedule.list') }}">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card_body callout callout-danger">
                    <span class="text-danger text-bold">Select Date and Time to schedule EWaybill extension for future
                        date.</span>
                    <br /><br />
                    <p>
                    @if(\Carbon\Carbon::now(App\Libraries\Helpers\Helper::getTimeZone())->gt(\Carbon\Carbon::parse(\Carbon\Carbon::createFromFormat('H:i',env('AUTO_SCHEDULER_TIME'), App\Libraries\Helpers\Helper::getTimeZone()))))
                    <input type="date" name="schedule_datetime" id="schedule_datetime" required min="{{\Carbon\Carbon::now()->addDay(1)->format('Y-m-d')}}" />
                    @else
                    <input type="date" name="schedule_datetime" id="schedule_datetime" required min="{{\Carbon\Carbon::now()->format('Y-m-d')}}" />
                    @endif
                    </p>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop


@section('adminlte_js')
@stop
