@extends('layouts.master')

@section('title')
Channel list
@endsection

@section('content')

@php
use \App\Http\Controllers\PickupController;
@endphp
<!-- CONTAINER -->
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW-1 -->
    <div class="row">
        <div class="col-12 mb-3">
            Hello {{Auth::user()->buisness_name}} | <small class="badge bg-success">{{ucwords(Auth::user()->roles->pluck('name')[0])}}</small>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12 ">
            <div class="card border p-0 overflow-hidden shadow-none">
                <div class="card-header ">
                    <div class="media mt-0">
                        <div class="media-body">
                            <h4 class="mb-0 mt-1">Channels List</h4>
                            <h6 class="mb-0 mt-3 text-muted">List of channel with their statuses .</h6>
                        </div>
                    </div>
                </div>

                <div class="card-body py-3 px-4" style="max-height: 295px; overflow-y: scroll;   scrollbar-width: none;">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table  text-nowrap text-sm-nowrap table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Channel</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="channelList">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="divLoader" class="text-center pt-5" style="height:300px;">
                            <span>
                                <i class="fe fe-spinner fa-spin"></i> Cameras are being loading.. It
                                might take few
                                seconds.
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>






    </div>
    <!-- ROW-1 END -->

</div>
<!-- CONTAINER END -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script>
    $(document).ready(function() {
        $(function() {
            setInterval(sitesList, 2000);
        });

        function sitesList() {
            $.ajax({
                url: '/api/ChannelList',
                type: "post",
                dataType: "JSON",
                cache: false,
                beforeSend: function() {},
                complete: function() {},
                success: function(response) {
                    // console.log(response)
                    if (response["status"] == "success") {
                        $("#channelList").html(response['data']);
                        $("#divLoader").css('display', 'none');
                    } else if (response["status"] == "fail") {

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

    });
</script>
@endsection