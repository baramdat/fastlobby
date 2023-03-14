@extends('layouts.master')

@section('title')
    Add Site
@endsection

@section('content')
    <style>
        .link-btn {
            color: #282F53;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
    <!-- CONTAINER -->
    <div class="main-container container-fluid">


        <!-- PAGE-HEADER Breadcrumbs-->
        <div style="margin-top:20px">
            Hello {{ Auth::user()->first_name }} | <small
                class="badge bg-success">{{ ucwords(Auth::user()->roles->pluck('name')[0]) }}</small>
        </div>
        <div class="page-header">
            <h1 class="page-title">Configure Wayfinder</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a> </li>
                    <li class="breadcrumb-item"><a href="{{ url('/site/list') }}">Wayfinder List</a> </li>
                    <li class="breadcrumb-item active" aria-current="page">Configure Wayfinder</li>

                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Configure Wayfinder</h3>
                </div>
                <div class="card-body">
                    <form action="" class="address" id="add_loctaion_form">
                        @csrf
                        <div class="row">
                            <div class="form-group form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="exampleInputname" class="form-label mb-0">START</label>
                                <input type="text" class="form-control" id="start" name="start"
                                    placeholder="Enter Starting Point" required>
                            </div>

                            <div class="form-group form-group col-lg-6 col-md-6 col-sm-12">
                                <label for="exampleInputname" class="form-label mb-0"
                                    style="vertical-align: middle;">END</label>
                                <input type="text" class="form-control" id="end" name="end"
                                    placeholder="Enter Ending Point" required>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary " id="next"
                                style="border: 1px solid #555555; border-radius: 6px;background: none;">Next</button>
                        </div>

                    </form>
                    <div class="form-group form-group col-lg-6 col-md-6 col-sm-12 text-end">
                        <button class="btn btn-light mt-2" id="start-camera"
                            style="border: 1px solid #555555;border-radius: 6px;background: none;display:none;">Open
                            Camera</button>
                    </div>
                    <div class="row take_picture">
                        <div class="col-lg-3">
                            <input type="hidden" name="loction" id="loction">
                            <label for="exampleInputname" id="textlabel" class="form-label mb-0"
                                style="display:none;">Text</label>
                            <input type="text" class="form-control mt-3 mb-2" name="description" id="description"
                                style="display:none" placeholder="Enter text">
                            <label for="exampleInputname" id="imagelabel" class="form-label mb-0"
                                style="display:none;">Image</label>
                            <video id="video" width="280" height="200" autoplay></video>
                            <a id="click-photo" class="btn btn-light mt-2"
                                style="border: 1px solid #555555;border-radius: 6px;background: none;display:none">Click
                                Photo</a>
                            <div id="canvas_show">
                                <canvas id="canvas" width="280" height="200"></canvas>
                                <a id="save-photo" class="btn btn-light mt-2 "
                                    style="border: 1px solid #555555;border-radius: 6px;background: none;display:none">Save</a>
                            </div>

                        </div>


                    </div>
                    <div class="save_done" style="display: none">
                        <div class="col-lg-3">
                            <a class="btn btn-light mt-2 start-camera" id="take_pictures"
                                style="border: 1px solid #555555;border-radius: 6px;background: none;">Take Picture</a>
                        </div>
                        <div class="col-lg-3">
                            <a class="btn btn-light mt-2 " id="finish"
                                style="border: 1px solid #555555;border-radius: 6px;background: none;">Finish/Publish</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- CONTAINER END -->
@endsection

@section('bottom-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(e) {

            // add user

            $("#add_loctaion_form").on('submit', (function(e) {

                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({

                    url: '/api/add/wayfinder/location',

                    type: "POST",

                    data: formData,

                    dataType: "JSON",

                    processData: false,

                    contentType: false,

                    cache: false,

                    beforeSend: function() {

                        $("#next").attr('disabled', true);

                        $(".fa-pulse").css('display', 'inline-block');

                    },

                    complete: function() {

                        $("#next").attr('disabled', false);

                        $(".fa-pulse").css('display', 'none');

                    },

                    success: function(response) {

                        if (response["status"] == "fail") {

                            toastr.error('Failed', response["msg"]);

                        } else if (response["status"] == "success") {
                            $('#loction').val(response["loc_id"]);
                            $('.address').css('display', 'none');
                            $('#start-camera').css('display', 'block');
                            $("#add_loctaion_form")[0].reset();

                        }

                    },

                    error: function(error) {

                        // console.log(error);
                        toastr.error('Failed', response["msg"]);

                    }

                });

            }));

            $('.start-camera').on('click', function(e) {
                $('.take_picture').css('display', '');
                $('.save_done').css('display', 'none');
                $('#canvas_show').css('display', 'none');
                $('#video').css('display', '');
                $("#click-photo").css('display', '');
            });
            $('#finish').on('click', function(e) {
                location.reload();
            });
        });
        $('body').on('click', '#start-camera', async function(ev) {
            let camera_button = document.querySelector("#start-camera");
            let video = document.querySelector("#video");
            let click_button = document.querySelector("#click-photo");
            let save_photo = document.querySelector("#save-photo");
            $("#start-camera").css('display', 'none');
            $("#click-photo").css('display', 'block');
            $('#description').css('display', 'block');
            $('html').find('#textlabel').css('display', 'block');
            $('html').find('#imagelabel').css('display', 'block');


            let canvas = document.querySelector("#canvas");

            let stream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
            });
            video.srcObject = stream;
            click_button.addEventListener('click', function() {
                $('#canvas_show').css('display', '');
                $('#save-photo').css('display', 'block');
                $('#video').css('display', 'none');
                $("#click-photo").css('display', 'none');
                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            });
            save_photo.addEventListener('click', function() {
                let des = $('#description').val();
                let location = $("#loction").val();
                let image_data_url = canvas.toDataURL('image/jpeg');
                let file = dataURItoFile(image_data_url, 'image.jpg');
                let postData = new FormData();
                postData.append('description', des);
                postData.append('file', file);
                postData.append('location', location);
                $.ajax({
                    async: true,
                    type: "post",
                    dataType: 'json',
                    contentType: false,
                    url: '/api/add/wayfinder/picture',
                    data: postData,
                    processData: false,
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success('Success', data.msg)
                            $("#description").val('');
                            $(".take_picture").css('display', 'none')
                            $(".save_done").css('display', 'block')

                            // console.log(image_data_url);
                        }
                    },
                    error: function(data, errorThrown) {
                        // alert('request failed :' + errorThrown);
                    },
                    xhr: function() {
                        let xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                let percentComplete = evt.loaded / evt
                                    .total;
                                let pers = parseInt(percentComplete *
                                    100);
                                let bar_per = 5;
                                if (pers > 5) {
                                    bar_per = (pers / 5) * 5;
                                }
                                let class_p = 'width: ' + bar_per + '%';
                            }
                        }, false);
                        xhr.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                let percentComplete = evt.loaded / evt
                                    .total;
                            }
                        }, false);
                        return xhr;
                    }
                });
            });

        });

        function dataURItoFile(dataURI, fileName) {
            let byteString = atob(dataURI.split(',')[1]);
            let mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
            let ab = new ArrayBuffer(byteString.length);
            let ia = new Uint8Array(ab);
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new File([ab], fileName, {
                type: mimeString,
                lastModified: Date.now()
            });
        }
    </script>
@endsection
