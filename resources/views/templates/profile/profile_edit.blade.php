@extends('layouts.master')

@section('title')
Edit Profile
@endsection

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">
    <style>
        #user_photo_selected {
            width: 100%;
            height: 100%;
        }
    </style>

    <!-- PAGE-HEADER Breadcrumbs-->
      <div style="margin-top:20px">
        Hello {{Auth::user()->first_name}} | <small class="badge bg-success">{{ucwords(Auth::user()->roles->pluck('name')[0])}}</small>
    </div>
    <div class="page-header">
        <h1 class="page-title">Edit Profile</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item"><a href="{{url('/profile')}}">Profile</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW-1 -->
    <div class="row">
        <div class="col-xl-12">

            <div class="card">
                <!-- <div class="text-end pt-2 px-3">
                    <a role="button" class="btn btn-dealer" href="{{url('/user/add')}}">
                        <span class="fe fe-edit fs-14"></span> Edit profile</a>
                </div> -->
                <!-- <div class="card-header">
                    <h3 class="card-title">Edit Profile</h3>
                </div> -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12 col-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Change Image</div>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center chat-image mb-5">
                                                <div class="avatar avatar-xxl chat-profile mb-3 brround">
                                                    @if(Auth::user()->image==NULL)
                                                    <img id="user_photo_selected" alt="avatar"
                                                        src="{{asset('assets/images/users/avatar-121.png')}}"
                                                        class="brround"></a>

                                                    @else
                                                    <img id="user_photo_selected"
                                                        src="{{asset('/uploads/files/'.Auth::user()->image)}}" alt=""
                                                        class="brround">
                                                    @endif
                                                </div>

                                                <div class="main-chat-msg-name">
                                                    <h5 class="mb-1 text-dark fw-semibold">
                                                        {{ucwords(Auth::user()->first_name) }}
                                                        {{ ucwords(Auth::user()->last_name)  }}</h5>

                                                    <!-- <p class="text-muted mt-0 mb-0 pt-0 fs-13">Web Designer</p> -->
                                                </div>
                                            </div>
                                            <form id="photo_upload">
                                                <div class="col-12">
                                                    <input type="file" class="form-control imageChange" accept="image/*"
                                                        required id="imageChange" name="photo" id="photo">

                                                </div>
                                                <div id="photo-error" class="text-muted small mt-1 text-center">
                                                    Allowed JPG, GIF or PNG. Max size of 2MB
                                                </div>
                                                <div class="text-center mt-2">
                                                    <button type="submit" class="btn btn-dealer btnSubmit"
                                                        id="btnSubmit"> <i class="fa fa-spinner fa-pulse" id="fa-pulse"
                                                            style="display: none;"></i>
                                                        Change photo</button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">Edit Password</div>
                                        </div>
                                        <div class="card-body">
                                            <form id="change_password">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="form-label">Current Password: <span class="text-danger">*</span></label>
                                                    <div class="wrap-input100 validate-input input-group"
                                                        id="Password-toggle">
                                                        <a href="javascript:void(0)"
                                                            class="input-group-text bg-white text-muted">
                                                            <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                                        </a>
                                                        <input class="input100 form-control" type="password" required
                                                            name="current_password" placeholder="Current Password">
                                                    </div>
                                                    <!-- <input type="password" class="form-control" value="password"> -->
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">New Password: <span class="text-danger">*</span></label>
                                                    <div class="wrap-input100 validate-input input-group"
                                                        id="Password-toggle1">
                                                        <a href="javascript:void(0)"
                                                            class="input-group-text bg-white text-muted">
                                                            <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                                        </a>
                                                        <input class="input100 form-control" type="password" required
                                                            name="new_password" placeholder="New Password">
                                                    </div>
                                                    <!-- <input type="password" class="form-control" value="password"> -->
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Confirm Password: <span class="text-danger">*</span></label>
                                                    <div class="wrap-input100 validate-input input-group"
                                                        id="Password-toggle2">
                                                        <a href="javascript:void(0)"
                                                            class="input-group-text bg-white text-muted">
                                                            <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                                        </a>
                                                        <input class="input100 form-control" type="password" required
                                                            name="confirm_password" placeholder="Confirm Password">
                                                    </div>
                                                    <!-- <input type="password" class="form-control" value="password"> -->
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 text-center pb-3">
                                                        <button type="submit" id="btnSubmitPassword"
                                                            class="btn btn-dealer px-4">
                                                            <i class="fa fa-spin fa-spinner" id="bx-pass"
                                                                style="display: none"></i> Save Password</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="row col-xl-12 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Edit Profile</h3>
                                    </div>
                                    <div class="card-body">
                                        <form id="update_profile">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12">
                                                    <label class="form-label mb-0">First name : <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="" name="first_name"
                                                        value="{{ucwords(Auth::user()->first_name) }}">
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <label class="form-label mb-0">Last name : <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="" name="last_name"
                                                        value="{{ ucwords(Auth::user()->last_name) }}">
                                                </div>
                                                                                                @if(Auth::user()->hasRole('Tenant'))
                                                <div class="col-lg-6 col-md-12">
                                                    <label class="form-label mb-0">Buisness name : <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="" name="buisness_name" value="{{ ucwords(Auth::user()->buisness_name) }}">
                                                </div>
                                                @endif
                                                
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label mb-0">Phone number: </label>
                                                        <input type="text" class="form-control telephone_number" id="ph_number" name="ph_number"
                                                            value="{{Auth::user()->phone }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label mb-0">Email : <span class="text-danger">*</span></label>
                                                        <input class="form-control" type="email" name="email"
                                                            value="{{Auth::user()->email}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-end">
                                                <button type="submit" class="btn btn-dealer px-4" id="btnSubmitProfile">
                                                    <i class="fa fa-spin fa-spinner" id="profile-spin"
                                                        style="display: none"></i>
                                                    Save
                                                    Changes</button>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ROW-1 END -->

</div>
<!-- CONTAINER END -->
@endsection

@section('bottom-script')
<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
<script>
    $(document).ready(function (e) {
    $('.telephone_number').mask('+1999-999-9999');
        // photo preview
        $(document).on('change', '.imageChange', function (e) {
            // alert('hello')
            photoError = 0;
            if (e.target.files && e.target.files[0]) {
                // console.log(e.target.files[0].name.strtolower())
                if (e.target.files[0].name.match(/\.(jpg|jpeg|JPG|png|gif|PNG)$/)) {
                    $("#photo-error").empty();
                    photoError = 0;
                    var reader = new FileReader();

                    reader.onload = function (e) {
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

        $("#photo_upload").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/change/photo',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $("#btnSubmit").attr('disabled', true);
                    $("#fa-pulse").css('display', 'inline-block');
                },
                complete: function () {
                    $("#btnSubmit").attr('disabled', false);
                    $("#fa-pulse").css('display', 'none');
                },
                success: function (response) {
                    if (response["status"] === "fail") {
                        toastr.error('Failed', response["msg"]);
                    } else if (response["status"] === "success") {
                        toastr.success('Success', response["msg"]);
                        location.reload()
                    } else if (response["status"] === "error") {
                        PhotoErrorMsg(response["msg"]);
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });
        }));

        function PhotoErrorMsg(msg) {
            $.each(msg, function (key, value) {
                toastr.error('Failed', msg);
            });
        }


        // UpdateProfile

        $("#update_profile").on('submit', (function (e) {
            // alert('asdk');
            e.preventDefault();
            var formData = new FormData(this);
            var phone = $("#ph_number").val();
            formData.append('ph_number',phone);
            $.ajax({
                url: '/api/profile/update',
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $("#btnSubmitProfile").attr('disabled', true);
                    $("#profile-spin").css('display', 'inline-block');
                },
                complete: function () {
                    $("#btnSubmitProfile").attr('disabled', false);
                    $("#profile-spin").css('display', 'none');
                },
                success: function (response) {
                    console.log(response);
                    if (response["status"] === "fail") {
                        if (response["msg"] instanceof Array) {
                            printErrorMsg(response["msg"]);
                        }else{
                            toastr.error('Failed', response["msg"]);
                        }
                    } else if (response["status"] === "success") {
                        toastr.success('Success', response["msg"]);
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }));

        function printErrorMsg(msg) {
            $.each(msg, function (key, value) {
                toastr.error('Failed', msg);
            });
        }

        // change Password
        $("#change_password").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/change/password',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $("#btnSubmitPassword").attr('disabled', true);
                    $("#bx-pass").css('display', 'inline-block');
                },
                complete: function () {
                    $("#btnSubmitPassword").attr('disabled', false);
                    $("#bx-pass").css('display', 'none');
                },
                success: function (response) {
                    // console.log(response);
                    if (response["status"] === "fail") {
                        toastr.error('Failed', response["msg"]);
                    } else if (response["status"] === "success") {
                        toastr.success('Success', response["msg"]);
                        $("#change_password")[0].reset();
                    }

                },
                error: function (error) {
                    // console.log(error);
                }
            });
        }));

        function disableAlert(alr) {
            $("#" + alr).css("display", "none");
        }
    });

</script>
@endsection
