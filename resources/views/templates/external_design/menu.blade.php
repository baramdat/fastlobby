<!doctype html>

<html lang="en" dir="ltr">



<head>



    <!-- META DATA -->

    <meta charset="UTF-8">

    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="Dealer Reg – Dealer Reg">

    <meta name="author" content="Spruko Technologies Private Limited">

    <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(env('APP_ICON')) }}" />







    <!-- TITLE -->

    <title>Menus – {{ env('APP_NAME') }} </title>



    @include('includes.style')


    <style>
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(112, 105, 105, 0.12), 0 4px 8px rgba(0, 0, 0, .06);
        }
    </style>

</head>



<body class="app sidebar-mini ltr light-mode mt-5">



    <div class="container ">
        <div class="row mt-5">
            
            
            <div class="col-xl-9">
                <h3>Please Select Menu</h3>
            </div>
        </div>
        <div class="row mt-5">

            <div class="col-xl-12">

                <div class="row mt-5">

                    <div class="col-lg-3 col-sm-12">
                        <a href="{{ url('all/promotional/videos').'/'.$site->id }}" id="pomotional_videos"target="_blank"
                            style="hover:background-color: rgb(60, 163, 70);">
                            <div class="card">
                                <img class="card-img-top" src="{{ asset('/uploads/external/promotional.png') }}"
                                    alt="Card image cap" height="130px">
                                <div class="card-body">
                                    <h5 class="card-title">Promotional Videos</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <a href="/external/scan/{{$site->id}}" id="pomotional_videos"target="_blank"
                            style="hover:background-color: rgb(60, 163, 70);">
                            <div class="card">
                                <img class="card-img-top" src="{{ asset('/uploads/external/scan-qr.jpg') }}"
                                    alt="Card image cap" height="130px">
                                <div class="card-body">
                                    <h5 class="card-title">Qr Scanner</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-12">
                        <a href="{{ url('contact/tenant').'/'.$site->id }}" id="pomotional_videos"target="_blank"
                            style="hover:background-color: rgb(60, 163, 70);">
                            <div class="card">
                                <img class="card-img-top" src="{{ asset('/uploads/external/tenantt.avif') }}"
                                    alt="Card image cap" height="130px">
                                <div class="card-body">
                                    <h5 class="card-title">Contact Tenant</h5>
                                </div>
                            </div>
                        </a>
                    </div>
  
                </div>
            </div>

            <!-- ROW-1 END -->
        </div>

        <!-- BACK-TO-TOP -->

        <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

        @include('includes.script')



</body>



</html>
