@extends('layouts.master')



@section('title')
    Qr Code list
@endsection



@section('content')
    <div class="main-container container-fluid">



        <!-- PAGE-HEADER Breadcrumbs-->

        <div style="margin-top:20px">

            Hello {{ Auth::user()->first_name }} | <small
                class="badge bg-success">{{ ucwords(Auth::user()->roles->pluck('name')[0]) }}</small>

        </div>

        <div class="page-header">

            @if (Auth::user()->hasRole('BuildingAdmin'))
                <div>

                    <h1 class="page-title">Qr Code type</h1>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Qr Code type</li>

                    </ol>

                </div>

                <a role="button" class="btn btn-dealer" href="{{ url('/qr/code/type/add') }}">

                    <span class="fe fe-user-plus fs-14"></span> Add Qr Code Type</a>
            @endif

            @if (Auth::user()->hasRole('Admin'))
                <div>

                    <h1 class="page-title">Qr Code type</h1>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Qr Code type</li>

                    </ol>

                </div>

                <a role="button" class="btn btn-dealer" href="{{ url('/qr/code/type/add') }}">

                    <span class="fe fe-user-plus fs-14"></span> Add Qr Code Type</a>
            @endif

        </div>

        <!-- PAGE-HEADER END -->

        <!-- filters -->

        <div class="card">

            <div class="card-body px-3 py-2 pt-3">

                <div class="form-row align-items-center">

                    <div class="col-6 col-lg-6 mb-1">

                        <label for="" class="fw-bold mb-1">Search by name:</label>

                        <input type="text" id="search" class="form-control" placeholder="search by name....">

                    </div>



                    <div class="col-6 col-lg-2 mb-1">

                        <label for="" class="mb-1"></label>



                        <button id="btnFilter" type="button" class="btn btn-dealer btn-block">Filter</button>

                    </div>

                    <div class="col-6 col-lg-2 mb-1">

                        <label for="" class="mb-1"></label>



                        <button id="btnReset" type="button" class="btn btn-outline-info btn-block">Reset</button>

                    </div>



                </div>

            </div>

        </div>



        <div class="row">

            <div class="col-12 col-sm-12">

                <div class="card">

                    <div class="card-header  mx-1">

                        <div class="media">

                            <div class="media-body">

                                <h6 class="mb-0 mt-1 text-muted">Sites list</h6>

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

                                                        <th class="bg-transparent border-bottom-0">

                                                            Name</th>

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

                                                <i class="fe fe-spinner fa-spin"></i> Sites are being loading.. It

                                                might take few

                                                seconds.

                                            </span>

                                        </div>

                                        <div class="row text-center" id="divNotFound" style="display:none">

                                            <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i>

                                                {{ __('No Site Found') }} !

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
        $(document).ready(function(e) {

            var filterLength = 1;

            var total = 0;

            var filterTitle = $("#search").val();

            var contentPagination = $("#content-pagination");

            var contentNotFound = $("#divNotFound");

            var contentFound = $("#divData");

            var filterRole = $("#filterRole").val();



            function setFilters() {

                filterTitle = $("#search").val()

                filterLength = 8;

            }

            userCount();



            function userCount() {

                setFilters()

                $("#tBody").html('');

                contentPagination.twbsPagination('destroy');

                $.ajax({

                    url: '/api/qr/code/type/count/',

                    type: "get",

                    data: {

                        filterTitle: filterTitle,

                        filterLength: filterLength,

                        filterRole: filterRole,

                    },

                    dataType: "JSON",

                    cache: false,

                    beforeSend: function() {},

                    complete: function() {},

                    success: function(response) {

                        // console.log(response);

                        if (response["status"] == "success") {

                            total = response["data"];
                            $("#tBody").html('');

                            initPagination(Math.ceil(total / filterLength));

                        } else if (response["status"] == "fail") {

                            $("#divNotFound").css('display', 'block')

                            $("#divLoader").css('display', 'none')

                            $("#divData").css('display', 'none')

                        }

                    },

                    error: function(error) {

                        // console.log(error);

                    }

                });

            }



            function users(offset, filterLength) {

                setFilters()

                $("#content-pagination").css('display', 'none')



                $("#tBody").html('');

                $("#divLoader").css('display', 'block')

                $("#divData").css('display', 'none')

                $("#divNotFound").css('display', 'none')



                $.ajax({

                    url: '/api/qr/code/list/',

                    type: "get",

                    data: {

                        filterTitle: filterTitle,

                        filterLength: filterLength,

                        offset: offset,

                        filterRole: filterRole,

                    },

                    dataType: "JSON",

                    cache: false,

                    beforeSend: function() {



                    },

                    complete: function() {



                    },

                    success: function(response) {

                        // console.log(response);

                        if (response["status"] == "fail") {

                            $("#divLoader").css('display', 'none')

                            // $("#divData").css('display', 'none')
                            $("#tBody").html('');

                            $("#divNotFound").css('display', 'block')

                        } else if (response["status"] == "success") {

                            $("#divNotFound").css('display', 'none')

                            $("#divLoader").css('display', 'none')

                            //  $("#divData").css('display', 'block')

                            $("#divData").html('');

                            $("#tBody").append(response["rows"])

                            $("#content-pagination").css('display', 'flex')

                        }

                    },

                    error: function(error) {

                        // console.log(error);

                    }

                });

            }



            function initPagination(totalPages) {



                if (totalPages > 0) {

                    contentPagination.show();

                    contentPagination.twbsPagination({

                        totalPages: totalPages,

                        visiblePages: 4,

                        onPageClick: function(event, page) {

                            users((page === 1 ? page - 1 : ((page - 1) * filterLength)), filterLength);

                        }

                    });

                } else {

                    contentPagination.hide();

                    contentFound.hide();

                    contentNotFound.show();

                }

            }



            $(document).on('keyup', '#search', function() {

                $("#tBody").html('');

                setFilters()

                contentPagination.twbsPagination('destroy');

                userCount()

            });

            $(document).on('click', '#btnFilter', function(e) {

                setFilters()

                $("#divData").html('')

                contentPagination.twbsPagination('destroy');

                userCount()

            })





            $(document).on('click', '#btnReset', function(e) {

                $("#search").val('')

                $("#filterRole").val('All')

                $("#filterType").val('All')

                setFilters()

                $("#divData").html('')

                contentPagination.twbsPagination('destroy');

                userCount()

            })







            $(document).on('click', '.btnDelete', function(e) {

                var id = $(this).attr('id')

                Swal.fire({

                        title: "Are you sure?",

                        text: "You will not be able to recover this Site type!",

                        type: "warning",

                        buttons: true,

                        confirmButtonColor: "#ff5e5e",

                        confirmButtonText: "Yes, delete it!",

                        closeOnConfirm: false,

                        dangerMode: true,

                        showCancelButton: true

                    })

                    .then((deleteThis) => {

                        if (deleteThis.isConfirmed) {

                            $.ajax({

                                url: '/api/delete/qr/code/type/' + id,

                                type: "delete",

                                dataType: "JSON",

                                success: function(response) {



                                    if (response["status"] == "fail") {

                                        Swal.fire("Failed!", "Failed to delete qr code.",

                                            "error");

                                    } else if (response["status"] == "success") {

                                        Swal.fire("Deleted!", "Qr code has been deleted.",

                                            "success");

                                        userCount()

                                    }

                                },

                                error: function(error) {

                                    // console.log(error);

                                },

                                async: false

                            });

                        } else {

                            Swal.close();

                        }

                    });

            });

        });
    </script>
@endsection
