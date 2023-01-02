@extends('layouts.master')

@section('title')
Add Appointment
@endsection

@section('content')
<!-- CONTAINER -->

<style>
    .hide {
        display: none;
    }
      .cursor-left {
        text-align: left;
    }
</style>

<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <h1 class="page-title">Add Appointment</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item"><a href="{{url('/appointment/list')}}">Appointments</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Add Appointment</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add Appointment</h3>
            </div>
            <div class="card-body">
                <form id="add_form">
                    @csrf
                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <label class="form-label mb-0">Visitor: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="John Doe.." required>
                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <label class="form-label mb-0">Email Address: <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="example@example.com" required>
                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <label class="form-label mb-0">Phone Number: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control telephone_number cursor-left" name="phone" placeholder="+13020..">
                            <span id="valid-msg" class="hide">✓ Valid</span>
                            <span id="error-msg" class="hide"></span>
                        </div>

                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <label class="form-label mb-0">Appointment Date: <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date">
                        </div>
                        <div class="col-lg-2 col-xl-2 col-md-4 col-sm-6 mb-1">
                        <label class="fw-bold mb-1">Appointment Time: <span class="text-danger">*</span></label>
                        <select class="form-control form-select" id="time" name="time" required>
                            <option value="">choose time</option>
                            <option value="08-09 am">08-09 AM</option>
                            <option value="09-10 am">09-10 AM</option>
                            <option value="10-11 am">10-11 AM</option>
                            <option value="11-12 am">11-12 AM</option>
                            <option value="12-01 pm">12-01 PM</option>
                            <option value="01-02 pm">01-02 PM</option>
                            <option value="02-03 pm">02-03 PM</option>
                            <option value="03-04 pm">03-04 PM</option>
                            <option value="04-05 pm">04-05 PM</option>
                            <option value="05-06 pm">05-06 PM</option>
                            <option value="06-07 pm">06-07 PM</option>
                            <option value="07-08 pm">07-08 PM</option>
                            <option value="08-09 pm">08-09 PM</option>
                            <option value="09-10 pm">09-10 PM</option>
                            <option value="10-11 pm">10-11 PM</option>
                            <option value="11-12 pm">11-12 PM</option>
                        </select>
                    </div>
                        <div class="form-group col-lg-12 col-sm-12 text-end">
                            <button type="submit" class="btn btn-dealer w-50 btnSubmit" id="btnSubmit">
                                <i class="fa fa-spinner fa-pulse" style="display: none;"></i>
                                Forward Appointment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- CONTAINER END -->
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
        // add forms
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
                    // console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        $("#add_form")[0].reset();
                    }
                },
                error: function(error) {
                    // console.log(error);
                }
            });
        }));

    });
</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection