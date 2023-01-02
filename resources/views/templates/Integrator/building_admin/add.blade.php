@extends('layouts.master')



@section('title')

User add

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

        <h1 class="page-title">Building Admin Add</h1>

        <div>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>

                <li class="breadcrumb-item"><a href="{{url('integrator/user/list')}}">Building Admin list</a></li>

                <li class="breadcrumb-item active" aria-current="page">Building Admin add</li>

            </ol>

        </div>

    </div>



    <!-- PAGE-HEADER END -->



    <!-- ROW -->

    <?php



        $sitesArr = \App\Models\Site::where('status', 'active')->where('user_id',auth()->user()->id)->get();

    ?>

    <div class="row">



        <div class="col-lg-12">

            <div class="card">

                <div class="card-body">

                    @if(sizeof($sitesArr)<1)

                    <div class="alert alert-warning">

                        <p>Please add your sites before inviting any building admin</p>

                    </div>

                    @endif

                    <form action="" id="add_user">

                        @csrf

                        <div class="row">

                            <div class="col-lg-6">

                                <div class="form-group">

                                    <label for="exampleInputname" class="form-label mb-0">First name: <span class="text-danger">*</span> </label>

                                    <input type="text" class="form-control" id="first_name" name="first_name" required placeholder="First name">

                                </div>

                            </div>

                            <div class="col-lg-6">

                                <div class="form-group">

                                    <label for="exampleInputname1" class="form-label mb-0">Last name: <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control" id="last_name" required name="last_name" placeholder="Enter last name">

                                </div>

                            </div>

                        </div>

                        
                        <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label mb-0">Job Starts At: <span class="text-danger">*</span></label>
                                    <select class="form-control form-select" id="start_time" name="start_time" required>
                                        <option value="">choose time</option>
                                        <option value="08 am">8 AM</option>
                                        <option value="09 am">9 AM</option>
                                        <option value="10 am">10 AM</option>
                                        <option value="11 am">11 AM</option>
                                        <option value="12 am">12 PM</option>
                                        <option value="01 pm">1 PM</option>
                                        <option value="02 pm">2 PM</option>
                                        <option value="03 pm">3 PM</option>
                                        <option value="04 pm">4 PM</option>
                                        <option value="05 pm">5 PM</option>
                                        <option value="06 pm">6 PM</option>
                                        <option value="07 pm">7 PM</option>
                                        <option value="08 pm">8 PM</option>
                                        <option value="09 pm">9 PM</option>
                                        <option value="10 pm">10 PM</option>
                                        <option value="11 pm">11 PM</option>
                                        <option value="12 pm">12 PM</option>
                                    </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label mb-0">Job Ends At: <span class="text-danger">*</span></label>
                                      <select class="form-control form-select" id="end_time" name="end_time" required>
                                                                            <option value="">choose time</option>
                                        <option value="08 am">8 AM</option>
                                        <option value="09 am">9 AM</option>
                                        <option value="10 am">10 AM</option>
                                        <option value="11 am">11 AM</option>
                                        <option value="12 am">12 PM</option>
                                        <option value="01 pm">1 PM</option>
                                        <option value="02 pm">2 PM</option>
                                        <option value="03 pm">3 PM</option>
                                        <option value="04 pm">4 PM</option>
                                        <option value="05 pm">5 PM</option>
                                        <option value="06 pm">6 PM</option>
                                        <option value="07 pm">7 PM</option>
                                        <option value="08 pm">8 PM</option>
                                        <option value="09 pm">9 PM</option>
                                        <option value="10 pm">10 PM</option>
                                        <option value="11 pm">11 PM</option>
                                        <option value="12 pm">12 PM</option>
                                    </select>
                            </div>
                        </div>
                    </div>
<div class="row">



                            <div class="col-lg-6" id="divSiteId" style="">

                                <div class="form-group">

                                    <label class="form-label mb-0">Site:</label>



                                    <select class="form-control form-select" id="site_id" name="site_id" >

                                        <option value="">choose site</option>

                                        @if(isset($sitesArr) && sizeof($sitesArr)>0)

                                            @foreach($sitesArr as $site)

                                                <option value="{{$site->id}}">{{ucwords($site->name)}}</option>

                                            @endforeach

                                        @endif

                                    </select>

                                </div>

                            </div>



                            <div class="col-lg-6">

                                <div class="form-group">

                                    <label for="exampleInputEmail1" class="form-label mb-0">Email address: <span class="text-danger">*</span></label>

                                    <input type="email" class="form-control" name="email" required id="email" placeholder="Email address">

                                </div>

                            </div>

                            <div class="col-lg-6">

                                <div class="form-group">

                                    <label for="" class="fw-bold mb-1">Phone Number: <span class="text-danger">*</span></label>

                                    <input type="text" class="form-control telephone_number cursor-left" name="phone" id="phone" placeholder="+13020..">

                                </div>

                            </div>

                        </div>
                        <div class="card-footer text-end">

                            <button type="submit" class="btn btn-primary btnSubmit" 

                                id="btnSubmit" {{sizeof($sitesArr)>0 ? '':'disabled'}}>

                                <i class="fa fa-spinner fa-pulse" style="display: none;"></i> Save

                            </button>

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

        $("#add_user").on('submit', (function(e) {

            e.preventDefault();

            var formData = new FormData(this);

            var phone = $("#phone").val();

            formData.append('phone', phone);

            $.ajax({

                url: '/api/integrator/user/add',

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

                        $("#add_user")[0].reset();

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