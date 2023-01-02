@extends('layouts.master')

@section('title')
Locker Booking History
@endsection

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <div>
            <h1 class="page-title">Locker Booking History</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item"><a href="{{url('/locker/details/'.$locker->id)}}">Locker #{{strtoupper($locker->number)}}</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Locker Booking History</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card">
                <div class="card-header  mx-1">
                    <div class="media">
                        <div class="media-body">
                            <h6 class="mb-0 mt-1 text-muted">Locker Booking History</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="grid-margin">
                        <div class="">
                            <div class="panel panel-primary">
                                <div class="panel-body tabs-menu-body border-0 pt-0">
                                    <div class="table-responsive">
                                        <table id="data-table" class="table table-bordered text-nowrap mb-0">
                                            <thead class="border-top">
                                                <tr>
                                                    <th class="bg-transparent border-bottom-0" style="width: 5%;">
                                                        ID</th>
                                                    <th class="bg-transparent border-bottom-0" style="width: 5%;">
                                                        Order #</th>
                                                    <th class="bg-transparent border-bottom-0" style="width: 5%;">
                                                        Pickup Code</th>
                                                    <th class="bg-transparent border-bottom-0">
                                                        Booked By</th>
                                                    <th class="bg-transparent border-bottom-0">
                                                        Customer</th>
                                                    <th class="bg-transparent border-bottom-0">
                                                        Status</th>
                                                    <th class="bg-transparent border-bottom-0">
                                                        Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tBody">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="divLoader" class="text-center pt-5" style="height:300px;">
                                        <span>
                                            <i class="fe fe-spinner fa-spin"></i> Booking history is being loading.. It
                                            might take few
                                            seconds.
                                        </span>
                                    </div>
                                    <div class="row text-center" id="divNotFound" style="display:none">
                                        <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i>
                                            {{__('No Booking History Found')}} !
                                        </h6>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div id="divPagination" class="text-center">
                                            <ul id="content-pagination" class="pagination-sm justify-content-end"
                                                style="display:none;"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom-script')
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function (e) {

        var filterLength = 1;
        var total = 0;
        var filterLockerNo = $("#search").val();
        var contentPagination = $("#content-pagination");
        var contentNotFound = $("#divNotFound");
        var contentFound = $("#divData");
        var filterLocker = '{{$locker->id}}'
        
        function setFilters() {
            filterLocker = '{{$locker->id}}'
            filterLength = 10;
        }

        bookingCount()

        function bookingCount() {
            $("#tBody").html('');
            setFilters()
            contentPagination.twbsPagination('destroy');

            $.ajax({
                url: '/api/pickup/locker/count',
                type: "get",
                data: {
                    filterLocker: filterLocker,
                    filterLength: filterLength
                },
                dataType: "JSON",
                cache: false,
                beforeSend: function () {},
                complete: function () {},
                success: function (response) {
                    if (response["status"] == "success") {
                        total = response["data"];
                        initPagination(Math.ceil(total / filterLength));
                        $("#tBody").html('');
                    } else if (response["status"] == "fail") {
                        $("#tBody").html('');
                        $("#divNotFound").css('display', 'block')
                        $("#divLoader").css('display', 'none')
                        $("#divData").css('display', 'none')
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        function bookings(offset) {
            setFilters()
            $("#content-pagination").css('display', 'none')

            $("#tBody").html('');
            $("#divLoader").css('display', 'block')
            $("#divData").css('display', 'none')
            $("#divNotFound").css('display', 'none')
            $.ajax({
                url: '/api/pickup/locker/list',
                type: "get",
                data: {
                    filterLocker: filterLocker,
                    filterLength: filterLength,
                    offset: offset
                },
                dataType: "JSON",
                cache: false,
                beforeSend: function () {

                },
                complete: function () {

                },
                success: function (response) {
                    if (response["status"] == "fail") {
                        $("#tBody").html('');

                        $("#divLoader").css('display', 'none')
                        $("#divData").css('display', 'none')
                        $("#content-pagination").css('display', 'none')
                        $("#divNotFound").css('display', 'block')
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        $("#divNotFound").css('display', 'none')
                        $("#divLoader").css('display', 'none')
                        $("#tBody").append(response["rows"]);
                        $("#divData").css('display', 'block');
                        $("#content-pagination").css('display', 'flex')

                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }

        function initPagination(totalPages) {
            if (totalPages > 0) {
                contentPagination.show();
                contentPagination.twbsPagination({
                    totalPages: totalPages,
                    visiblePages: 4,
                    onPageClick: function (event, page) {
                        bookings((page === 1 ? page - 1 : ((page - 1) * filterLength)), filterLength);
                    }
                });
            } else {
                contentPagination.hide();
                contentFound.hide();
                contentNotFound.show();
            }
        }

    });

</script>
@endsection


 