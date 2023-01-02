@extends('layouts.master')

@section('title')
Locker Details
@endsection

@section('content')

@php
use \App\Http\Controllers\LockerController;
@endphp

<!-- CONTAINER -->
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div style="margin-top:20px">
        Hello {{Auth::user()->first_name}} | <small class="badge bg-success">{{ucwords(Auth::user()->roles->pluck('name')[0])}}</small>
    </div>
    <div class="page-header">
        <h1 class="page-title">Locker Details</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item"><a href="{{url('/locker/list')}}">Lockers</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Locker Details</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Details of Locker: <span class="text-uppercase">{{$locker->number}}<span></h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <tr>
                            <td>Locker #</td>
                            <th>{{$locker->number}}</th>
                        </tr>
                        <tr>
                            <td>Size</td>
                            <th>{{$locker->size->size}}</th>
                        </tr>
                        <tr>
                            <td>Row</td>
                            <th>{{$locker->row}}</th>
                        </tr>
                        <tr>
                            <td>Column</td>
                            <th>{{$locker->column}}</th>
                        </tr>
                        <tr>
                            <td>Relay #</td>
                            <th>{{$locker->relay}}</th>
                        </tr>
                        <tr>
                            <td>Current Status</td>
                            <td>
                                <?php
                                echo LockerController::status($locker->status);
                                if ($locker->status == 'occupied') {
                                    echo ' By <a href="mailto:' . $locker->occupied->email . '">' . $locker->occupied->email . '</a> Until: <badge class="badge bg-info">' . date('m/d/Y H:i:s a') . '</badge>';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="javascript:;" state="2" lockerId="{{$locker->id}}" relay="{{$locker->relay}}" class="btn btn-danger btn-sm relayState">Open Locker</a>

                                <!-- <a  href="javascript:;" state="0" lockerId="{{$locker->id}}" relay="{{$locker->relay}}" class="btn btn-secondary btn-sm relayState">Close Locker</a> -->

                                <a class="btn btn-sm btn-primary" href="{{url('/locker/booking-history/'.$locker->id)}}">View Booking History</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- CONTAINER END -->
@endsection

@section('bottom-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {

        $("#btnSubmit").on('click', (function(e) {
            $("#update_form").submit()
        }));

        $("#update_form").on('submit', (function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '/api/locker/update',
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spinner").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spinner").css('display', 'none');
                },
                success: function(response) {
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                    }
                },
                error: function(error) {
                    // console.log(error);
                }
            });


        }));

        // open close relay
        $(document).on('click', '.relayState', function(e) {
            var lockerId = $(this).attr('lockerId');
            var relay = $(this).attr('relay');
            var state = $(this).attr('state');

            $.ajax({
                url: '/api/relay/state/update',
                type: "POST",
                dataType: "JSON",
                data: {
                    lockerId: lockerId,
                    relay: relay,
                    state: state
                },
                success: function(response) {
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                    }
                },
                error: function(error) {
                    // console.log(error);
                },
                async: false
            });
        });
    });
</script>
@endsection