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

    <title>Appointment detail – {{env('APP_NAME')}} </title>



    @include('includes.style')



</head>



<body class="app sidebar-mini ltr light-mode">



    <div class="container">

        <div class="row mt-5">

            <div class="col-xl-12">



                <div class="card">

                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6 text-center decision">
                                <p>Click approve for accept invitation or decline it.</p>

                                <div  class="decision mb-4">
                                    <a href="javascript:;" class="btn btn-primary btnInform" data-id="{{$visitor->id}}" data="approve"><i class="fa fa-spinner fa-spin fa-approve" style="display:none;"></i> Approve</a>
                                    <a href="javascript:;" class="btn btn-danger btnInform" data-id="{{$visitor->id}}" data="decline"><i class="fa fa-spinner fa-spin fa-decline" style="display:none;"></i> Decline</a>
                                </div>                                
                            </div>
                            <div class="col-lg-6 text-center resultDiv" style="display: none;">
                                <p><span class="alert alert-success">Your request is updated!</span></p>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>

                        <div class="row">

                            <div class="col-lg-4">

                                <div class="text-center chat-image mb-5">

                                    <div class=" chat-profile mb-3 brround">

                                        @if(isset($client->image) && $client->image !=NULL)

                                        <img id="" src="{{asset('uploads/files/'.$client->image)}}" style="width:175px;height:175px;object-fit:cover;" alt="" class="img-fluid rounded-circle mx-auto">

                                        @else

                                        <img id="" alt="avatar" src="{{asset('assets/images/users/avatar-121.png')}}" style="width:175px;height:175px;object-fit:cover;" class="brround" style="width:200px !important"></a>

                                        @endif

                                    </div>



                                    <div class="main-chat-msg-name">

                                        <h3 class="mb-1 text-dark fw-semibold">

                                        {{ucwords($client->first_name)}} {{ucwords($client->last_name)}}

                                        </h3>

                                    </div>

                                    <div class="d-flex mt-4 text-center text-truncate" style="max-witdh:125px;">

                                        <div>

                                            <span>

                                                <i class="fe fe-mail fs-20"></i>

                                            </span>

                                        </div>

                                        <div>

                                            <span class="mx-2">

                                            {{ucwords($client->email)}}

                                            </span>

                                        </div>

                                    </div>

                                    <div class="d-flex mt-2">

                                        <div>

                                            <span>

                                                <i class="fa fa-map-marker fs-20"></i>

                                            </span>

                                            <span class="mx-2">

                                                <strong>{{ucwords($client->site->name)}}</strong>

                                            </span><br>

                                            <span>

                                            {{ucwords($client->site->address)}}

                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-lg-8">

                                <div class="card">

                                    <div class="card-header">

                                        <strong>Walk-in Visitor Information</strong>

                                    </div>

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between">

                                            <div>

                                                <div>

                                                    <h4 class="mx-2">

                                                        <strong>{{ucwords($visitor->name)}}</strong>

                                                    </h4>

                                                </div>

                                                <div class="d-flex mt-2">

                                                    <div>

                                                        <span>

                                                            <i class="fa fa-envelope fs-20"></i>

                                                        </span>

                                                    </div>

                                                    <div>

                                                        <span class="mx-2">

                                                            {{$visitor->email}}

                                                        </span>

                                                    </div>

                                                </div>

                                                <div class="d-flex mt-2">

                                                    <div>

                                                        <span>

                                                            <i class="fa fa-phone fs-20"></i>

                                                        </span>

                                                    </div>

                                                    <div>

                                                        <span class="mx-2">

                                                            {{$visitor->phone}}

                                                        </span>

                                                    </div>

                                                </div>

                                                <div class="d-flex mt-2">

                                                    <div>

                                                        <span>

                                                            <i class="fe fe- fs-20"></i>

                                                        </span>

                                                    </div>

                                                    <div>

                                                        <span class="mx-2">

                                                            {{$visitor->address}}

                                                        </span>

                                                    </div>

                                                </div>

                                                <div class="d-flex mt-2">

                                                    <div>

                                                        <span>

                                                            <i class="fa fa-map-marker fs-20"></i>

                                                        </span>

                                                    </div>

                                                    <div>

                                                        <span class="mx-2">

                                                            {{$visitor->city}}

                                                        </span>

                                                    </div>

                                                </div>

                                                <div class="d-flex mt-2">

                                                    <div>

                                                        <span>

                                                            <i class="fa fa-globe fs-20"></i>

                                                        </span>

                                                    </div>

                                                    <div>

                                                        <span class="mx-2">

                                                            <strong>{{$visitor->country}}</strong>

                                                        </span>

                                                    </div>

                                                </div>

                                            </div>



                                            

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







    <!-- BACK-TO-TOP -->

    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>



    @include('includes.script')

    <script>

    $('.resultDiv').hide();

    $(document).on('click','.btnInform',function(e){

        var id = $(this).attr('data-id');

        var status = $(this).attr('data');
        
        var spin= $('.fa-'+status);

        var btn=$(this);

        e.preventDefault();

            $.ajax({

                url: '/api/visitor/qr/request/update',

                type: "POST",

                data: {id:id,status:status},

                dataType: "JSON",

                cache: false,

                beforeSend: function () {

                    btn.attr('disabled', true);

                    spin.css('display', 'inline-block');



                },

                complete: function () {

                    btn.attr('disabled', false);

                    spin.css('display', 'none');

                },

                success: function (response) {

                    if (response["status"] == "fail") {

                        toastr.error('Failed', response["msg"]) 

                    } else if (response["status"] == "success") {

                        toastr.success('Success', response["msg"])

                        $('.decision').hide();
                                
                        $('.resultDiv').show();

                    }

                },

                error: function (error) {

                    console.log(error);

                }

            });

    })

    </script>

</body>



</html>