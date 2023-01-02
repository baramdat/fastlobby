<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{env('APP_DESC')}}">
    <meta name="author" content="{{env('APP_AUTHOR')}}">
    <meta name="keywords" content="{{env('APP_KEYWORDS')}}">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset(env('APP_ICON'))}}" />


    <!-- TITLE -->
    <title>Reset Password – {{env('APP_NAME')}}</title>
    <style>
        .page {
            background: rgb(16 14 38 / 60%) !important;
        }
    </style>
    <!-- BOOTSTRAP CSS -->
    @include('includes.style')

</head>

<body class="app sidebar-mini ltr">

    <!-- BACKGROUND-IMAGE -->
    <div class="login-img">

        <!-- GLOABAL LOADER -->
        <div id="global-loader">
            <img src="{{asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader">
        </div>
        <!-- End GLOABAL LOADER -->

        <!-- PAGE -->
        <div class="page">
            <div class="">

                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto mt-2">
                    <div class="text-center">
                        <img src="{{asset(env('APP_LOGO_WHITE'))}}" class="header-brand-img" alt="" style="height:auto !important; width:137px !important;">
                    </div>
                </div>

                <!-- CONTAINER OPEN -->
                <div class="container-login100">
                    <div class="wrap-login100 p-6">
                        <form class="login100-form validate-form" id="change_password">
                            <div class="alert alert-warning">
                                <p>In order to secure your account, Please change your password.</p>
                            </div>
                            <p class="text-muted">Make sure your new password and confirm password are same.</p>
                            <div class="form-group">
                                <label class="form-label">Current Password: <span class="text-danger">*</span></label>
                                <div class="wrap-input100 validate-input input-group" id="Password-toggle1">
                                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                        <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                    </a>
                                    <input class="input100 form-control" type="password" required name="current_password" placeholder="Current Password">
                                </div>
                                <!-- <input type="password" class="form-control" value="password"> -->
                            </div>
                            <div class="form-group">
                            <label class="form-label">New Password: <span class="text-danger">*</span></label>
                                <div class="wrap-input100 validate-input input-group" id="Password-toggle1">
                                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                        <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                    </a>
                                    <input class="input100 form-control" type="password" required name="new_password" placeholder="New Password">
                                </div>
                                <!-- <input type="password" class="form-control" value="password"> -->
                            </div>
                            <div class="form-group">
                            <label class="form-label">Confirm Password: <span class="text-danger">*</span></label>
                                <div class="wrap-input100 validate-input input-group" id="Password-toggle2">
                                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                        <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                    </a>
                                    <input class="input100 form-control" type="password" required name="confirm_password" placeholder="Confirm Password">
                                </div>
                                <!-- <input type="password" class="form-control" value="password"> -->
                            </div>
                            <div class="submit">
                                <button type="submit" class="btn btn-primary" id="btnSubmitPassword">
                                    <i class="fa fa-spinner fa-pulse" style="display: none"></i>
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--END PAGE -->

    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

    @include('includes.script')

    <script type="text/javascript">
                // change Password
            $("#change_password").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/change/password',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmitPassword").attr('disabled', true);
                    $("#bx-pass").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmitPassword").attr('disabled', false);
                    $("#bx-pass").css('display', 'none');
                },
                success: function(response) {
                    // console.log(response);
                    if (response["status"] === "fail") {
                        toastr.error('Failed', response["msg"]);
                    } else if (response["status"] === "success") {
                        toastr.success('Success', response["msg"]);
                        $("#change_password")[0].reset();
                        window.location.href = "{{url('/login')}}";
                    }
                },
                error: function(error) {
                    // console.log(error);
                }
            });
        }));
    </script>
</body>

</html>