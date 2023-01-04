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
    <script src="{{asset('assets/js/howler.js')}}"></script>
    <script src="{{asset('assets/js/jquery-visibility.js')}}"></script>
    <script>

        
        var title = '';
        title = $(document).attr('title');
        $(document).ready(function() {
            var is_chat_room = "{{Request::is('room/join/*')}}"
            var room_id = '';
            if(is_chat_room == 1){
                var room_id = "{{ collect(request()->segments())->last() }}"
            }
            console.log(room_id,"{{Request::is('room/join/*')}}")
            var sound = new Howl({
                src: ['/assets/ring/ring.mp3']
            });
            // mesageNotification();
            // videoMessageNotification();
            // inactiveVideoMessageNotification();

            $(function() {
                setInterval(mesageNotification, 5000);
                setInterval(videoMessageNotification, 5000);
                setInterval(inactiveVideoMessageNotification, 5000);
            });

            function mesageNotification() {

                $.ajax({
                    url: '/api/message/notifications',
                    type: "get",
                    dataType: "JSON",
                    cache: false,
                    beforeSend: function() {},
                    complete: function() {

                    },
                    success: function(response) {
                        
                        if (response["status"] == "fail") {
                            $(".message-head").html(response["head"])
                        } else if (response["status"] == "success") {
                            if (response["unread"] > 0) {
                                //$(".unread").html(response["unread"])
                                $('.unread').css('display', 'block');
                            } else {
                                //$(".unread").html("");
                                $(".unread").css('display', 'none');
                            }

                            $(".message-head").html(response["head"])

                            $(".messages-body").html('')
                            $(".messages-body").prepend(response["messages"])

                            setTimeout(mesageNotification, 5000);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });

            }

            function videoMessageNotification() {
                $.ajax({
                    url: '/api/video/chat/notifications',
                    type: "get",
                    dataType: "JSON",
                    cache: false,
                    beforeSend: function() {

                    },
                    complete: function() {},
                    success: function(response) {
                        if (response["status"] == "fail") {
                            $(".video_message-head").html(response["head"])
                        } else if (response["status"] == "success") {
                            if (response["unread"] > 0) {
                                //$(".unread").html(response["unread"])
                                $('.video_unread').css('display', 'block');
                                $('#IncomingVideoCall').modal('show');
                                if(!sound.playing()){
                                    console.log('not playing')
                                    sound.play();
                                }                                
                            } else {
                                //$(".unread").html("");
                                $(".video_unread").css('display', 'none');
                                $('#IncomingVideoCall').modal('hide');
                            }
                            if (response["url"] != '') {
                                $(".approve").attr("href", response['url']);
                            }
                            $(".video_message-head").html(response["head"])
                            $("#exampleModalLabel").html(response["Upcoming video call"])

                            $(".video_messages-body").html('')
                            $(".modal-body").html('')
                            $(".video_messages-body").prepend(response["messages"]);
                            $(".modal-body").html(response["messages"]);
                            $("#videoDecline").attr("data-id", response['notificationId']);
                            console.log('id :' + response['notificationId'])
                            setTimeout(videoMessageNotification, 5000);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
            //video notification function for inactive tabs 
            function inactiveVideoMessageNotification() {
                $.ajax({
                    url: '/api/video/chat/notifications',
                    type: "get",
                    dataType: "JSON",
                    cache: false,
                    beforeSend: function() {

                    },
                    complete: function() {},
                    success: function(response) {
                        // console.log(response)
                        if (response["status"] == "fail") {
                            $(".video_message-head").html(response["head"])
                        } else if (response["status"] == "success") {
                            if (response["unread"] > 0) {
                                //$(".unread").html(response["unread"])
                                $('.video_unread').css('display', 'block');
                                $('#IncomingVideoCall').modal('show');
                                $(document).attr("title", "Incoming video call");
                                sound.play();
                            } else {
                                //$(".unread").html("");
                                $(".video_unread").css('display', 'none');
                                $('#IncomingVideoCall').modal('hide');
                                $(document).attr("title", title);
                            }
                            if (response["url"] != '') {
                                $(".approve").attr("href", response['url']);
                            }
                            $(".video_message-head").html(response["head"])
                            $("#exampleModalLabel").html(response["Upcoming video call"])

                            $(".video_messages-body").html('')
                            $(".modal-body").html('')
                            $(".video_messages-body").prepend(response["messages"]);
                            $(".modal-body").html(response["messages"]);
                            $("#videoDecline").attr("data-id", response['notificationId']);
                            console.log('id :' + response['notificationId'])
                            setTimeout(inactiveVideoMessageNotification, 5000);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }

            $(document).on('click', '#videoDecline', function() {
                var id = $(this).attr('data-id');
                $.ajax({
                    url: '/api/single/notification/read/' + id,
                    type: "get",
                    dataType: "JSON",
                    cache: false,
                    beforeSend: function() {

                    },
                    complete: function() {},
                    success: function(response) {
                        console.log(response);
                        $(document).attr("title", title);
                        $(".video_messages-body").html('');
                        $(".modal-body").html('');
                        $(".video_unread").css('display', 'none');
                        sound.stop();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            const mq = window.matchMedia("(min-width: 960px)");

            if (mq.matches) {
                $('.app-sidebar__toggle').click();
            } else {
                $(".app .app-sidebar").addClass('sidenav-toggled');
                // $(".app .app-sidebar").css('width','80px');
                // $(".app .app-sidebar").css('overflow','hidden');
            }
        })
    </script>
</head>

<body class="app sidebar-mini ltr light-mode">

    <!-- GLOBAL-LOADER -->
    <!--<div id="global-loader">-->
    <!--    <img src="{{asset('assets/images/loader.svg')}}" class="loader-img" alt="Loader">-->
    <!--</div>-->
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!-- app-Header -->
            @include('includes.header')
            <!-- /app-Header -->

            <!--APP-SIDEBAR-->
            @include('includes.sidebar')

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    @yield('content')
                    <!-- CONTAINER END -->
                    <div class="modal fade" id="IncomingVideoCall" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Incoming video call</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Somenone is inviting you for a video call..
                                </div>
                                <div class="modal-footer">
                                    <a href="" type="button" class="btn btn-primary approve">Join</a>
                                    <button type="button" class="btn btn-secondary" data-id="" data="" id="videoDecline" data-bs-dismiss="modal">Decline</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    <!-- Script for closed tab notification pop up -->
    // <script>
    //     $(function() {
    //         function inactiveEvent() {
    //             inactiveVideoMessageNotification();
    //             $(document).attr("title", "inactive event triggered!");
    //             console.log('triggered!');
    //         }

    //         function activeEvent() {
    //             $(document).attr("title", "active event triggered!");
    //             console.log('triggered!');
    //         }
    //         $(document).on({
    //             'show.visibility': function() {
    //                 $(document).attr("title", "tab active");
    //                 setTimeout(activeEvent, 5000);
    //             },
    //             'hide.visibility': function() {
    //                 $(document).attr("title", "tab closed");
    //                 setTimeout(inactiveEvent, 5000);
    //             }
    //         });

    //     });
    // </script>
</body>

</html>