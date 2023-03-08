@extends('layouts.master')



@section('title')
    Site Qr List
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

                    <h1 class="page-title">Site Qr List</h1>

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Site Qr List</li>

                    </ol>

                </div>
                <a role="button" class="btn btn-dealer" id="addQrCode" href="javascript:void(0)">

                    <span class="fa fa-qrcode"></span>  NEW QR</a>
            @endif

        </div>

        <!-- PAGE-HEADER END -->


        <div class="row">

            <div class="col-12 col-sm-12">

                <div class="card">

                    <div class="card-header  mx-1">
                        <div class="row">
                            <div class="media">

                                <div class="media-body">
    
                                    <h6 class="mb-0 mt-1 text-muted">Site Qr Codes</h6>
                                    
                                </div>
                                
    
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



                                                            Qr Code</th>
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

                                                <i class="fe fe-spinner fa-spin"></i> Sites qr are being loading.. It

                                                might take few

                                                seconds.

                                            </span>

                                        </div>

                                        <div class="row text-center" id="divNotFound" style="display:none">

                                            <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i>

                                                {{ __('No Site Qr Found') }} !

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

            filterLength = 10;
            var contentPagination = $("#content-pagination");

            var contentNotFound = $("#divNotFound");

            var contentFound = $("#divData");
            dataCount();

            function dataCount() {

                $("#tBody").html('');

                contentPagination.twbsPagination('destroy');



                $.ajax({

                    url: '/api/site/qr/count',

                    type: "get",

                    data: {
                        filterLength: filterLength,

                    },

                    dataType: "JSON",

                    cache: false,

                    beforeSend: function() {},

                    complete: function() {},

                    success: function(response) {

                        console.log(response);

                        if (response["status"] == "success") {

                            total = response["data"];

                            initPagination(Math.ceil(total / filterLength));

                            $("#tBody").html('');

                        } else if (response["status"] == "fail") {

                            $("#tBody").html('');

                            // toastr.error('Failed', response["msg"])

                            $("#divNotFound").css('display', 'block')

                            $("#divLoader").css('display', 'none')

                            $("#divData").css('display', 'none')

                        }

                    },

                    error: function(error) {

                        console.log(error);

                    }

                });

            }

            function sitQrCodes(offset) {


                $("#tBody").html('');

                $.ajax({

                    url: '/api/qr/code/genrate/list',

                    type: "get",

                    data: {

                        filterLength: filterLength,

                        offset: offset,

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

                            sitQrCodes((page === 1 ? page - 1 : ((page - 1) * filterLength)),
                                filterLength);

                        }

                    });

                } else {

                    contentPagination.hide();

                    contentFound.hide();

                    contentNotFound.show();

                }

            }
            $(document).on('click', '.btnRegenerate', function(e) {
                // 
                var id = $(this).attr('id')
                $.ajax({

                    url: '/api/generate/site/qr/' + id,

                    type: "post",

                    dataType: "JSON",
                    beforeSend: function() {

                        $(e).attr('disabled', true);

                    },

                    complete: function() {

                        $(e).attr('disabled', false);

                    },

                    success: function(response) {

                        if (response["status"] == "fail") {
                            toastr.error('Failed', response["msg"])
                        } else if (response["status"] ==
                            "success") {
                            toastr.success('Success', response["msg"])
                            dataCount()

                        }

                    },

                    error: function(error) {

                        // console.log(error);

                    },

                    async: false

                });
                
            });

            $(document).on('click', '.btnDelete', function(e) {

                var id = $(this).attr('id')

                Swal.fire({

                        title: "Are you sure?",

                        text: "You will not be able to recover this screen!",

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

                                url: '/api/site/qr/delete/' + id,

                                type: "delete",

                                dataType: "JSON",

                                success: function(response) {

                                    if (response["status"] == "fail") {

                                        Swal.fire("Failed!",
                                            "Failed to delete screen.",

                                            "error");

                                    } else if (response["status"] ==
                                        "success") {

                                        Swal.fire("Deleted!",
                                            "Screen has been deleted.",

                                            "success");

                                        dataCount()

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
