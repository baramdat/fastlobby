@extends('layouts.master')



@section('title')
Video List

@endsection



@section('content')

<!-- CONTAINER -->

<div class="main-container container-fluid">

    <div class="col-12 mb-3" style="margin-top:30px !important">

        Hello {{Auth::user()->first_name}} | <small class="badge bg-success">{{ucwords(Auth::user()->roles->pluck('name')[0])}}</small>



    </div>

    <!-- PAGE-HEADER Breadcrumbs-->

    <div class="page-header">

        <div>

            <h1 class="page-title">Video List</h1>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>

                <li class="breadcrumb-item active" aria-current="page">Video List</li>

            </ol>

        </div>

    </div>

    <!-- PAGE-HEADER END -->



    <!-- PAGE-HEADER Breadcrumbs-->



    <!-- PAGE-HEADER END -->

    <!-- filters -->

    <div class="card">

        <div class="card-body px-3 py-2 pt-3">

            <div class="form-row align-items-center">

                <div class="col-lg-3 col-xl-3 col-md-4 col-sm-6 mb-1">

                    <label for="" class="fw-bold mb-1">Search by name :</label>

                    <input type="text" id="search" class="form-control" placeholder="John Doe..">

                </div>

               

                <div class="col-lg-3 col-xl-3 col-md-4 col-sm-6 mb-1 ">

                    <label for="" class="mb-1"></label>



                    <button id="btnFilter" type="button" class="btn btn-dealer btn-block">Filter</button>

                </div>

                <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 mb-1 ">

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

                            <h6 class="mb-0 mt-1 text-muted">Video list</h6>

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

                                                    <th class="bg-transparent border-bottom-0" style="width: 20%;">

                                                        Id  </th>
                                                    <th class="bg-transparent border-bottom-0" style="width: 20%;">

                                                       Video  </th>

                                                    <th class="bg-transparent border-bottom-0" style="width: 20%;">

                                                       Description</th>

                                                    <th class="bg-transparent border-bottom-0" style="width: 10%;">

                                                        Actions</th>

                                                </tr>

                                            </thead>

                                            <tbody id="tBody">



                                            </tbody>

                                        </table>

                                    </div>

                                    <div id="divLoader" class="text-center pt-5" style="height:300px;">

                                        <span>

                                            <i class="fe fe-spinner fa-spin"></i> Appointments are being loading.. It

                                            might take few

                                            seconds.

                                        </span>

                                    </div>

                                    <div class="row text-center" id="divNotFound" style="display:none">

                                        <h6 class="mt-lg-5"><i class="bx bx-window-close"></i>

                                            {{__('No Video Found')}} !

                                        </h6>

                                    </div>

                                    <div class="col-lg-12 mt-3">

                                        <div id="divPagination" class="text-center">

                                            <ul id="content-pagination" class="pagination-sm justify-content-end" style="display:none;"></ul>

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

<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">

    $(document).ready(function(e) {





        var filterLength = 1;

        var total = 0;

        var filterSearch = $("#search").val();

        var contentPagination = $("#content-pagination");

        var contentNotFound = $("#divNotFound");

        var contentFound = $("#divData");

        function setFilters() {

            filterSearch = $("#search").val()

            filterStatus = $("#filterStatus").val();

            filterPhone = $("#filterPhone").val();



            filterLength = 10;

        }

        videoCount()



        // setInterval(function (data) {





        //     lockerCount()

        // }, 1000);

        function videoCount() {

            $("#tBody").html('');

            setFilters()

            contentPagination.twbsPagination('destroy');



            $.ajax({

                url: '/api/building/videos/count',

                type: "get",

                data: {

                    filterSearch: filterSearch,

                    filterLength: filterLength,

                },

                dataType: "JSON",

                cache: false,

                beforeSend: function() {},

                complete: function() {},

                success: function(response) {


                    if (response["status"] == "success") {

                        total = response["data"];

                        initPagination(Math.ceil(total / filterLength));

                        $("#tBody").html('');

                    } else if (response["status"] == "fail") {

                        $("#tBody").html('');

                        toastr.error('Failed', response["msg"])

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



        function videos(offset) {

            setFilters()

            $("#content-pagination").css('display', 'none')



            $("#tBody").html('');

            $("#divLoader").css('display', 'block')

            $("#divData").css('display', 'none')

            $("#divNotFound").css('display', 'none')

            $.ajax({

                url: '/api/building/videos/list',

                type: "get",

                data: {

                    filterSearch: filterSearch,
                    filterLength: filterLength,
                    offset: offset

                },

                dataType: "JSON",

                cache: false,

                beforeSend: function() {



                },

                complete: function() {



                },

                success: function(response) {


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

                        // statuses();

                        setInterval(statuses, 10000);

                    }

                },

                error: function(error) {

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

                    onPageClick: function(event, page) {

                        videos((page === 1 ? page - 1 : ((page - 1) * filterLength)), filterLength);

                    }

                });

            } else {

                contentPagination.hide();

                contentFound.hide();

                contentNotFound.show();

            }

        }



        function statuses() {



            $('table > tbody  > tr').each(function() {



                var id = $(this).attr('data-id');





                $.ajax({

                    url: '/api/appointment/status',

                    type: "get",

                    data: {



                        id: id,



                    },

                    dataType: "JSON",

                    cache: false,

                    beforeSend: function() {



                    },

                    complete: function() {



                    },

                    success: function(response) {



                        if (response["status"] == "fail") {



                        } else if (response["status"] == "success") {



                            $("#td_" + id).html(response["html"])

                        }

                    },

                    error: function(error) {

                        console.log(error);

                    }

                });



            });

        }



        $(document).on('keyup', '#search', function() {

            $("#tBody").html('');

            setFilters()

            videoCount()

        });



        $(document).on('click', '#btnFilter', function(e) {

            setFilters()

            videoCount()

        })



        $(document).on('click', '#btnReset', function(e) {

            $("#search").val('')
            setFilters()

            videoCount()

        })



        $(document).on('click', '.btnDelete', function(e) {

            var id = $(this).attr('id')

            Swal.fire({

                    title: "Are you sure?",

                    text: "You will not be able to recover this Appointment!",

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

                            url: '/api/video/delete/' + id,

                            type: "delete",

                            dataType: "JSON",

                            success: function(response) {

                                console.log(response)

                                if (response["status"] == "fail") {

                                    Swal.fire("Failed!", "Failed to delete appointment.",

                                        "error");

                                } else if (response["status"] == "success") {

                                    Swal.fire("Deleted!", "appointment has been deleted.",

                                        "success");

                                    videoCount()

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



        // edit form

        $(document).on('click', '.btnEdit', function(e) {

            var form = $(this).attr('id');

            $.ajax({

                url: '/api/edit/form/' + id,

                type: "GET",

                dataType: "JSON",

                data: {

                    form: form



                },

                success: function(response) {

                    // console.log(response);

                    if (response["status"] == "fail") {

                        // toastr.error('Failed', response["msg"])

                    } else if (response["status"] == "success") {

                        // toastr.success('Success', response["msg"])

                        // $("#name").val(response["data"]["name"])

                        // $("#role_id").val(response["data"]["id"])

                    }

                },

                error: function(error) {

                    // console.log(error);

                },

                async: false

            });

        });





        // open close relay

    });

</script>

<script>

    $(document).ready(function() {



        $('.telephone_number').mask('+1999-999-9999');





        $(document).on('click', '#btnSubmit', function() {

            $('#add_form').submit();

        })

        //add appointment form

        $("#add_form").on('submit', (function(e) {

            e.preventDefault();

            $.ajax({

                url: '/api/appointment/add',

                type: "POST",

                data: new FormData(this),

                dataType: "JSON",

                processData: false,

                contentType: false,

                cache: false,

                beforeSend: function() {

                    $("#btnSubmit").attr('disabled', true);

                    $(".fa-pulse").css('display', 'inline-block');

                },

                complete: function() {

                    $("#btnSubmit").attr('disabled', false);

                    $(".fa-pulse").css('display', 'none');

                },

                success: function(response) {

                    console.log(response);

                    if (response["status"] == "fail") {

                        toastr.error('Failed', response["msg"])

                    } else if (response["status"] == "success") {

                        toastr.success('Success', response["msg"])

                        $("#add_form")[0].reset();

                        location.reload();

                    }

                },

                error: function(error) {

                    console.log(error);

                }

            });

        }));

    });

</script>

@endsection