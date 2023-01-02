@extends('layouts.master')

@section('title')
Document Scan
@endsection

@section('content')
<!-- CONTAINER -->
<style>
    #heading {
        font-size: 20px;
        text-align: center;
        color: #5353f1;
        font-weight: 500;
    }

    #cardBody {
        border: 1px solid #4c4cef;
        border-radius: 10px;
        height: 30px;
        background: #dfdfdf;
        margin-bottom: 20px;
        margin-top: 20px;
    }

    .btn:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }
        .hide{
        display:none;
    }
    .show{
        display: block;
    }
    .item-flex{
        display:flex;
    }
</style>

<script language="JavaScript" src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script language="JavaScript" src="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div style="margin-top:20px">
        Hello {{Auth::user()->first_name}} | <small class="badge bg-success">{{ucwords(Auth::user()->roles->pluck('name')[0])}}</small>
    </div>
    <div class="page-header">
        <h1 class="page-title">ID Scan</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Document Scan</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <?php
    $site = App\Models\User::where('id', auth()->user()->id)->first();
    $clients = App\Models\User::whereHas('roles', function ($q) {
        $q->where('name', 'Tenant');
    })->where('site_id', $site->site_id)->get();


    ?>
    <div class="container">
        <div class="row" id="clientsDiv">

            <div class="col-md-12">
                <div class="card p-2">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Client Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Client Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>

                            <tbody>
                                @if(isset($clients) && sizeof($clients)>0)
                                <?php $i = 1; ?>
                                @foreach($clients as $client)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td class="text-center">
                                        <p>
                                            @if($client->image==NULL)
                                            <img src="{{asset('assets/images/users/21.jpg')}}" class="rounded-circle" style="width:80px;">
                                            @else
                                            <img src="{{asset('uploads/files/'.$client->image)}}" class="rounded-circle" style="width:80px;">
                                            @endif
                                        </p>

                                    </td>
                                    <td class="text-center">
                                        <b>{{ucwords($client->first_name)}} {{ucwords($client->last_name)}}</b>
                                    </td>
                                    <td>{{$client->email}}</td>
                                    <td>{{$client->phone}}</td>
                                    <td>{{$client->site->name}}, {{$client->site->address}}</td>
                                    <td>

                                        <button id="{{$client->id}}" class="btn btn-primary btn-xs btnClient" data-title="Select Client">
                                            <span class="glyphicon glyphicon-hand-up" style="font-size:20px;"></span>
                                        </button>

                                    </td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row hide" id="scanDiv">
        <div class="col-lg-3 col-xl-3"></div>
        <div class="col-lg-6 col-xl-6">
            <div class="alert alert-warning">
                The barcode on the photo should be well-defined. Make sure it’s not cropped or blurred.
            </div>
            <div class="col-12" id="cardBody">
                <h5 id="heading">Scan your document</h5>
            </div>
            <div class="card" style="">
                <div class="card-body" style="height:360px ;border: 2px dotted #9090eb;border-radius: 10px;">
                    <div class="row" id="btnDiv">
                        <div class="col-12" style="height:100px"></div>
                        <div class="col-12 text-center" style="height:100px">
                            <a href="javascript:;" class="btn btn-primary rounded-circle btnUpload">
                                <i class="fa fa-upload text-white" aria-hidden="true" style="font-size:16px;"></i>
                            </a><br>
                            <a href="javascript:;" class="btnUpload text-muted">Click here to upload image</a>
                        </div>
                        <div class="col-12" style="height:100px"></div>
                    </div>
                    <div id="imgDiv" style="display: none;">
                        <img src="" id="ImgSrc" style="object-fit:cover;width:100%;height:300px;">
                    </div>
                    <div id="screenshots"></div>
                    <div id="my_camera" style="display:none;">
                        <video autoplay id="video" style="height:300px;"></video>
                    </div>
                </div>
            </div>
            <div class="row" id="snapDiv" style="display:none;">
                <div class="col-6">
                    <button class="btn btn-primary w-100" id="btnScreenshot" style="display:none;"><i class="fa fa-camera text-white"></i> Take Snapshot</button>
                </div>
                <div class="col-6">
                    <button class="button" id="btnChangeCamera" style="display: none;">
                        <span class="icon">
                            <i class="fas fa-sync-alt"></i>
                        </span>
                        <span>Switch camera</span>
                    </button>
                </div>
            </div>

            <button class="btn btn-primary w-100" id="cameraMode"><i class="fa fa-camera text-white" disabled></i> Switch to
                camera mode</button>

            <form id="image_form">
                <button class="btn btn-primary w-100 mt-2" type="submit" id="documentSubmit" disabled><i class="fa fa-spinner fa-spin fa-doc" style="display:none;"></i> Submit</button>
                <input type="file" name="scan_file" id="inputFile" style="opacity:0;">
            </form>
            <a id="documentImg" href=""></a>
            <canvas class="is-hidden" id="canvas"></canvas>

        </div>

        <div class="col-lg-3 col-xl-3"></div>
    </div>
    <div class="row pt-5" id="resultDiv">

    </div>



</div>
<!-- CONTAINER END -->
@endsection

@section('bottom-script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>


<script>
    
         var imageFile = '';
        var clientId = '';
        var type = '';

        (function() {
            if (
                !"mediaDevices" in navigator ||
                !"getUserMedia" in navigator.mediaDevices
            ) {
                alert("Camera API is not available in your browser");
                return;
            }

            // get page elements
            const video = document.querySelector("#video");
            video.setAttribute('id','video-scan')
            const btnPlay = document.querySelector("#btnPlay");
            const btnPause = document.querySelector("#btnPause");
            const btnScreenshot = document.querySelector("#btnScreenshot");
            const btnChangeCamera = document.querySelector("#btnChangeCamera");
            const screenshotsContainer = document.querySelector("#screenshots");
            const canvas = document.querySelector("#canvas");
            const devicesSelect = document.querySelector("#devicesSelect");
            const cameraMode = document.querySelector("#cameraMode");
            
            const selectClient = document.querySelector(".btnClient");



            // video constraints
            const constraints = {
                video: {
                    width: {
                        min: 1280,
                        ideal: 1920,
                        max: 2560,
                    },
                    height: {
                        min: 720,
                        ideal: 1080,
                        max: 1440,
                    },
                },
            };

            // use front face camera
            let useFrontCamera = true;

            // current video stream
            let videoStream;

            // handle events
            // play
            // btnPlay.addEventListener("click", function () {
            //   video.play();
            //   btnPlay.classList.add("is-hidden");
            //   btnPause.classList.remove("is-hidden");
            // });

            // // pause
            // btnPause.addEventListener("click", function () {
            //   video.pause();
            //   btnPause.classList.add("is-hidden");
            //   btnPlay.classList.remove("is-hidden");
            // });

            // take screenshot
            btnScreenshot.addEventListener("click", function() {
                const img = document.createElement("img");
                img.setAttribute('id','img-scan')
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext("2d").drawImage(video, 0, 0);
                img.src = canvas.toDataURL("image/png");
                
                type = "base64"
                imageFile = canvas.toDataURL("image/png");
                screenshotsContainer.prepend(img);
                $("#screenshots").css('display', 'block');
                $("#my_camera").css('display', 'none');
                $("#cameraMode").css('display', 'block');
                $("#snapMode").css('display', 'none');
                $("#btnScreenshot").css('display', 'none');
                $("#btnChangeCamera").css('display', 'none');
                $("#documentSubmit").attr('disabled',false);
                $("#img-scan").css('max-height','300px')
                $("#img-scan").css('object-fit','contain')
                stopVideoStream();
                $("#image_form").submit();
            });

            selectClient.addEventListener("click", function() {

                var id = $(this).attr('id');
                clientId = id;

                $("#clientsDiv").addClass('hide');
                $("#scanDiv").removeClass('hide');                
                $("#cameraMode").click()
            });

            cameraMode.addEventListener("click", function() {
                $("#video-scan").css('width','100%')
                $("#screenshots").html('');
                $("#screenshots").css('display', 'none');
                $("#btnDiv").css('display', 'none');
                $("#imgDiv").css('display', 'none');
                $("#my_camera").css('display', 'block');
                $("#snapDiv").css('display', 'flex');
                $("#btnScreenshot").css('display', 'block');
                $("#btnChangeCamera").css('display', 'block');
                $("#cameraMode").css('display', 'none');
                
                initializeCamera();
                
            });

            // switch camera
            btnChangeCamera.addEventListener("click", function () {
              useFrontCamera = !useFrontCamera;

              initializeCamera();
            });

            // stop video stream
            function stopVideoStream() {
                if (videoStream) {
                    videoStream.getTracks().forEach((track) => {
                        track.stop();
                    });
                }
            }

            // initialize
            async function initializeCamera() {
                stopVideoStream();
                // constraints.video.facingMode = useFrontCamera ? "user" : "environment";
                constraints.video.facingMode = "environment";

                try {
                    videoStream = await navigator.mediaDevices.getUserMedia(constraints);
                    video.srcObject = videoStream;
                    // $("#btnChangeCamera").click()
                } catch (err) {
                    alert("Could not access the camera");
                }
            }

            // initializeCamera();
        })();
        $(document).ready(function() {
            $('#datatable').dataTable();

            $("[data-toggle=tooltip]").tooltip();

        });


        // $(document).on('click', '.btnClient', function() {
        //     var id = $(this).attr('id');
        //     clientId = id;
        //     $("#clientsDiv").css('display', 'none');
        //     $("#scanDiv").css('display', 'flex');

        // })
        $(document).on('click', '.btnUpload', function() {
            $("#inputFile").click();
        })

        $(document).on('change', '#inputFile', function(e) {
            var img = e.target.files[0];
            imageFile = e.target.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                $("#btnDiv").css('display', 'none');
                $("#imgDiv").css('display', 'block');
                $('#ImgSrc').attr('src', e.target.result);
                $("#documentImg").attr("href", reader.result);
                $("#documentSubmit").attr('disabled', false);
                type = "image";
            }
            reader.readAsDataURL(e.target.files[0]);


        });
        // add form
        $(document).on('submit', '#image_form', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('image', imageFile);
            formData.append('client_id', clientId);
            formData.append('type', type);
            $.ajax({
                url: '/api/webservice/image-scan',
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,

                beforeSend: function() {
                    $("#documentSubmit").attr('disabled', true);
                    $("#cameraMode").attr('disabled', true);
                    $(".fa-doc").css('display', 'inline-block');
                },
                complete: function() {
                    $("#documentSubmit").attr('disabled', false);
                    $(".fa-doc").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);

                    if (response["status"] == "success") {
                        //toastr.success('Success', response["msg"])

                        $("#btnDiv").css('display', 'block');
                        $("#imgDiv").css('display', 'none');
                        $('#ImgSrc').attr('src', '');
                        $("#documentSubmit").attr('disabled', true);
                        $("#clientsDiv").css('display','none');
                        $("#scanDiv").css('display','none');
                        $("#resultDiv").html(response["html"]);

                    } else if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])

                        $("#btnDiv").css('display', 'block');
                        $("#imgDiv").css('display', 'none');
                        $('#ImgSrc').attr('src', '');
                        $("#screenshots").html('');
                        $("#screenshots").css('display','none');
                        $("#documentSubmit").attr('disabled', true);

                    }
 
                },
                error: function(error) {
                    console.log(error);
                }
            });

        })

        $(document).on('click','#addVisitor',function(){
            var client_id = clientId
            var name = $("#full_name").html();
            var city = $("#city_value").html();
            var country = $("#country").html();
            var address = $("#address").html();
            var gender = $("#gender").html();


            $.ajax({
                url: '/api/apppointment/create',
                type: "POST",
                data: {
                    client_id:client_id,
                    name:name,
                    city:city,
                    country:country,
                    address:address,
                    gender:gender
                },
                dataType: "JSON",

                cache: false,

                beforeSend: function() {
                    $("#addVisitor").attr('disabled', true);
                    $(".fa-add").css('display', 'inline-block');
                },
                complete: function() {
                    $("#addVisitor").attr('disabled', false);
                    $(".fa-add").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);

                    if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])

                        $("#btnDiv").css('display', 'block');
                        $("#imgDiv").css('display', 'none');
                        $('#ImgSrc').attr('src', '');
                        $("#documentSubmit").attr('disabled', true);
                        $("#clientsDiv").css('display','flex');
                        $("#scanDiv").css('display','none');
                        $("#resultDiv").css('display','none');

                    } else if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])



                    }
 
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })

        $(document).on('click','#ResetBtn',function(){
            location.reload();
        })







</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection