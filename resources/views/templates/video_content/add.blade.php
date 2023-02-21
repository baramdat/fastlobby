@extends('layouts.master')



@section('title')

Post Video
@endsection



@section('content')

<style>

    .hide {

        display: none;

    }

</style>

<div class="main-container container-fluid">



    <!-- PAGE-HEADER Breadcrumbs-->

    <div style="margin-top:20px">

        Hello {{Auth::user()->first_name}} | <small class="badge bg-success">{{ucwords(Auth::user()->roles->pluck('name')[0])}}</small>

    </div>

    <div class="page-header">

        <h1 class="page-title">Post Video</h1>

        <div>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>

                <li class="breadcrumb-item"><a href="{{url('/user/list')}}">Video List</a></li>

                <li class="breadcrumb-item active" aria-current="page">Post Video</li>

            </ol>

        </div>

    </div>

  


    <!-- PAGE-HEADER END -->



    <!-- ROW -->

    <div class="row">

        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

            <div class="card">

                <div class="card-body">

                    <form action="" id="add_video">

                        @csrf

                            <div class="col-lg-12">

                                <div class="form-group">

                                    <textarea type="text" class="form-control" id="description" name="description" required placeholder="What is in your mind ....."rows="3"></textarea>

                                </div>

                            </div>

                            <div class="col-lg-12">

                                <div class="form-group">

                                    <label for="exampleInputname1" class="form-label mb-0">Video: <span class="text-danger">*</span></label>

                                    <input type="file" class="form-control" id="video" required name="video" >

                                </div>

                            </div>

                       

                        <div class="card-footer text-end">

                            <button type="submit" class="btn btn-primary btnSubmit" id="btnSubmit"> <i class="fa fa-spinner fa-pulse" style="display: none;"></i>

                                Post</button>

                            <!-- <input type="button" class="btn btn-danger my-1" value="Cancel"> -->

                        </div>

                    </form>

                </div>



            </div>

        </div>

    </div>

    <!-- ROW END -->

</div>





@endsection



@section('bottom-script')

<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>

<script>

    $("#roleId").on('change', function(e) {
        if ($(this).val() == 5) {
            $("#divSiteId").css('display', 'block');
        } else {
            $("#divSiteId").css('display', 'none');
        }
    });

    $('.telephone_number').mask('+1999-999-9999');

    var input = document.querySelector("#phone"),

        errorMsg = document.querySelector("#error-msg"),

        validMsg = document.querySelector("#valid-msg");



    // here, the index maps to the error code returned from getValidationError - see readme

    var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];



    // initialise plugin

    var iti = window.intlTelInput(input, {

        utilsScript: "../../build/js/utils.js?1638200991544"

    });



    var reset = function() {

        input.classList.remove("error");

        errorMsg.innerHTML = "";

        errorMsg.classList.add("hide");

        validMsg.classList.add("hide");

    };



    // on blur: validate

    input.addEventListener('blur', function() {

        reset();

        if (input.value.trim()) {

            if (iti.isValidNumber()) {

                validMsg.classList.remove("hide");

            } else {

                input.classList.add("error");

                var errorCode = iti.getValidationError();

                errorMsg.innerHTML = errorMap[errorCode];

                errorMsg.classList.remove("hide");

            }

        }

    });



    // on keyup / change flag: reset

    input.addEventListener('change', reset);

    input.addEventListener('keyup', reset);

</script>

<script>

    $(document).ready(function(e) {

        $(document).on('change', '.imageChange', function(e) {

            photoError = 0;

            if (e.target.files && e.target.files[0]) {

                // console.log(e.target.files[0].name.strtolower())

                if (e.target.files[0].name.match(/\.(jpg|jpeg|JPG|png|gif|PNG)$/)) {

                    $("#photo-error").empty();

                    photoError = 0;

                    var reader = new FileReader();



                    reader.onload = function(e) {

                        $('#user_photo_selected').attr('src', e.target.result);

                    }

                    reader.readAsDataURL(e.target.files[0]);



                } else {

                    photoError = 1;

                    $("#photo-error").empty();

                    $(".btnSubmit").prop('disabled', true);



                    $("#photo-error").append(

                        '<p class="text-danger">Please upload only jpg, png format!</p>');

                }

            } else {

                $('#user_photo_selected').attr('src', '');

            }



            if (photoError == 0) {

                $(".btn-change-photo").prop('disabled', false);

                $("#btnSubmit").attr('disabled', false);

            } else {

                $(".btn-change-photo").prop('disabled', true);

            }

        });



        // add user

        $("#add_video").on('submit', (function(e) {

            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({

                url: '/api/video/add',

                type: "POST",

                data: formData,

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

                    if (response["status"] == "fail") {

                        toastr.error('Failed', Array.isArray(response.msg) ? response.msg[0] : response.msg);

                    } else if (response["status"] == "success") {

                        toastr.success('Success', response["msg"])

                        $("#add_video")[0].reset();

                    }

                },

                error: function(error) {

                    // console.log(error);
                      toastr.error('Failed',response["msg"]);

                }

            });

        }));






    });

</script>



@endsection