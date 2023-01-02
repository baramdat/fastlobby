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
    <title>Register – {{env('APP_NAME')}}</title>

   <!-- STYLE CSS -->
   @include('includes.style')

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="../assets/colors/color1.css" />

</head>

<body class="app sidebar-mini ltr">

    <!-- BACKGROUND-IMAGE -->
    <div class="login-img">

        <!-- GLOABAL LOADER -->
        <div id="global-loader">
            <img src="../assets/images/loader.svg" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOABAL LOADER -->

        <!-- PAGE -->
        <div class="page">
            <div class="">
                <!-- CONTAINER OPEN -->
                <div class="col col-login mx-auto mt-7">
                    <div class="text-center">
                        <img src=   "{{asset(env('APP_LOGO_WHITE'))}}" class="header-brand-img m-0" alt="" style="height:59px !important;">
                    </div>
                </div>
                <div class="container-login100">
                    <div class="wrap-login100 p-6">
                        <form class="login100-form validate-form" id="add_user">
                            @csrf
                            <input class="input100 border-start-0 ms-0 form-control" type="hidden" name="app_id"
                                    requiered placeholder="First name" id="app_id" value="{{$id}}">
                            <span class="login100-form-title">
									Registration
								</span>
                            <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    <i class="mdi mdi-account" aria-hidden="true"></i>
                                </a>
                                <input class="input100 border-start-0 ms-0 form-control" type="text" name="first_name"
                                    requiered placeholder="First name">
                            </div>
                            <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    <i class="mdi mdi-account" aria-hidden="true"></i>
                                </a>
                                <input class="input100 border-start-0 ms-0 form-control" type="text" name="last_name"
                                    requiered placeholder="Last name">
                            </div>
                            <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    <i class="zmdi zmdi-email" aria-hidden="true"></i>
                                </a>
                                <input class="input100 border-start-0 ms-0 form-control" type="email" name="email"
                                    required placeholder="Email" value="{{$app->email}}" readonly>
                            </div>
                            <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    <i class="zmdi zmdi-eye" aria-hidden="true"></i>
                                </a>
                                <input class="input100 border-start-0 ms-0 form-control" type="password" name="password" required
                                    placeholder="Password">
                            </div>
                            <div class="wrap-input100 validate-input input-group" id="Phone">
                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    <i class="zmdi zmdi-phone" aria-hidden="true"></i>
                                </a>
                                <input class="input100 border-start-0 ms-0 form-control" type="text" name="password" required
                                    placeholder="Phone">
                            </div>
                            <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary btnSubmit" id="btnSubmit"> <i
                                        class="fa fa-spinner fa-pulse mx-2" style="display: none;"></i>
                                     Register</button>
                            </div>
                        </form>
                    </div>
                </div> 
                <!-- CONTAINER CLOSED -->
            </div>
        </div>
        <!-- END PAGE -->

    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

    @include('includes.script')
    <script>
        $(document).ready(function (e) {
            // add user
            var id = $("#app_id").val();
            $("#add_user").on('submit', (function (e) {
                e.preventDefault();
                $.ajax({
                    url: '/api/register/user',
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
                        // console.log(response);
                        if (response["status"] == "fail") {
                            toastr.error('Failed', response["msg"])
                        } else if (response["status"] == "success") {
                            toastr.success('Success', response["msg"])
                            window.location.href = "/guest/login/"+id;
                        }
                    },
                    error: function (error) {
                        // console.log(error);
                    }
                });
            }));

        });
 
    </script>

</body>

</html>