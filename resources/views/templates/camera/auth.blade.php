@extends('layouts.master')

@section('title')
Camera Auth
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
            <h1 class="page-title">Camera Authentication</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Camera Authentication</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <div class="row">
        <div class="col-3"></div>
        <div class="col-6">
            <div class="card">
                <div class="card-body px-3 py-2 pt-3">
                    <form id="add_form">
                        @csrf
                        <div class="col-12 mb-1">
                            <label for="" class="fw-bold mb-1">Username: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="JohnDoe123.." required>
                        </div>
                        <div class="col-12 mb-1">
                            <label for="" class="fw-bold mb-1">Password: <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="col-12 mb-1">
                            <label for="" class="fw-bold mb-1">Port: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="port" id="port" placeholder="26..">
                        </div>
                        <div class="col-12 mb-1 ">
                            <label for="" class="mb-1"></label>
                            <button id="btnSubmit" type="submit" class="btn btn-primary btn-block"><i class="fa fa-spinner fa-pulse" style="display: none;"></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-3"></div>

    </div>
</div>
@endsection

@section('bottom-script')
<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        //add appointment form
        $("#add_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/camera/auth/add',
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