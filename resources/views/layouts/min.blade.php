<!doctype html>

<html lang="en" dir="ltr">



<head>



    <!-- META DATA -->

    <meta charset="UTF-8">

    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="Dealer Reg – Dealer Reg">

    <meta name="author" content="Spruko Technologies Private Limited">

    <meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->

    <link rel="shortcut icon" type="image/x-icon" href="{{asset(env('APP_ICON'))}}" />







    <!-- TITLE -->

    <title>@yield('title') – {{env('APP_NAME')}} </title>



    @include('includes.style')

    <style>

        @media (max-width: 415px) and (height: 1368px) {

            .profileImg {

                width: 72px !important;

                height: 39px !important;

            }

        }

    </style>

    <script>

        $(document).ready(function() {





                const mq = window.matchMedia( "(min-width: 960px)" );



                if (mq.matches) {

                    $('.app-sidebar__toggle').click();

                } else {

                    $(".app .app-sidebar").addClass('sidenav-toggled');



                }



        })

    </script>







</head>



<body class="app sidebar-mini ltr light-mode">







    <!-- PAGE -->

    <div class="page" >

        <div class="page-main">





            <!--app-content open-->

                <div class="side-app">



                    <!-- CONTAINER -->

                    @yield('content')

                    <!-- CONTAINER END -->

                </div>

            <!--app-content close-->



        </div>









        <!-- FOOTER -->

        @include('includes.footer')

        <!-- FOOTER END -->



    </div>



    <!-- BACK-TO-TOP -->

    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>



    @include('includes.script')

</body>



</html>
