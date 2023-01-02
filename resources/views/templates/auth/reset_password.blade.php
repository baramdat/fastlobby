<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{env('APP_DESC')}}">
    <meta name="author" content="{{env('APP_AUTHOR')}}">
    <meta name="keywords"
        content="{{env('APP_KEYWORDS')}}">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset(env('APP_ICON'))}}" />

 
    <!-- TITLE -->
    <title>Reset Password – {{env('APP_NAME')}}</title>

    <!-- BOOTSTRAP CSS -->
    @include('includes.style')
        <style>
        .page{
            background: rgb(16 14 38 / 60%) !important;
        }
    </style>

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
                <div class="col col-login mx-auto">
                    <div class="text-center">
                        <img src="{{asset(env('APP_LOGO_WHITE'))}}" class="header-brand-img" alt=""
                            style="height:auto !important; width:137px !important;">
                    </div>
                </div>

                <!-- CONTAINER OPEN -->
                <div class="container-login100">
                    <div class="wrap-login100 p-6">
                        <form class="login100-form validate-form" id="reset_password">
                         
                            <span class="login100-form-title pb-5">
                                Reset Password
                            </span>
                            <input type="hidden" name="token" value="{{$token}}">
                            <p class="text-muted">Enter the new and confirm password to reset the password.</p>
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <div class="wrap-input100 validate-input input-group" id="Password-toggle1">
                                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                        <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                    </a>
                                    <input class="input100 form-control" name="password" required type="password" placeholder="New Password">
                                </div>
                                <!-- <input type="password" class="form-control" value="password"> -->
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <div class="wrap-input100 validate-input input-group" id="Password-toggle2">
                                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                        <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                    </a>
                                    <input class="input100 form-control" name="password_confirmation" required type="password" placeholder="Confirm Password">
                                </div>
                                <!-- <input type="password" class="form-control" value="password"> -->
                            </div>
                            <div class="submit">
                                <button type="submit" class="btn btn-primary" id="btnSubmit">
                                    <i class="fa fa-spinner fa-pulse" style="display: none"></i>
                                    Change Password
                                </button>
                            </div>
                            <div class="text-center mt-4">
                                <p class="text-dark mb-0">Forgot it?<a class="text-primary ms-1"
                                        href="{{url('/login')}}">Send me back</a></p>
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
    $("#reset_password").on('submit', (function (e) {
        e.preventDefault();
        $.ajax({
            url: '/api/reset-password',
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function () {
                $("#btnSubmit").attr('disabled', true);
                $(".fa-pulse").css('display', 'inline-block');
            },
            complete: function () {
                $("#btnSubmit").attr('disabled', false);
                $(".fa-pulse").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed',response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success',response["msg"])
                    window.location="/login";
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }));
</script>
</body>

</html>
