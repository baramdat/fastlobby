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
            <h1 class="page-title">Add Picture</h1>
            <div>
                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a> </li>
                    <li class="breadcrumb-item"><a href="{{ url('/site/list') }}">Picture List</a> </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Picture</li>

                </ol>
            </div>
        </div>
        <!-- PAGE-HEADER END -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Picture</h3>
                </div>
                <div class="card-body">
                    <div class="col-lg-3 text-center">
                        <a class="btn btn-light mt-2 " id="start-camera"
                            style="border: 1px solid #555555;border-radius: 6px;background: none;">Open
                            Camera</a>
                    </div>
                    <div class="col-lg-3">
                        <video id="video" width="220" height="170" autoplay></video>
                        <input type="form-control" name="description" id="description" style="display:none">
                        <a id="click-photo" class="btn btn-light mt-2 "
                            style="border: 1px solid #555555;border-radius: 6px;background: none;display:none">Click
                            Photo</a>
                        <canvas id="canvas" class="d-none" width="320" height="240"></canvas>
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
        $('body').on('click', '#start-camera', async function(ev) {
            let camera_button = document.querySelector("#start-camera");
            let video = document.querySelector("#video");
            let click_button = document.querySelector("#click-photo");
            $("#click-photo").css('display', 'block');
            $('#description').css('display', 'block');
            let canvas = document.querySelector("#canvas");
            let des=$('#description').val();
            let stream = await navigator.mediaDevices.getUserMedia({
                video: true,
                audio: false
            });
            video.srcObject = stream;
            click_button.addEventListener('click', function() {
                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                let image_data_url = canvas.toDataURL('image/jpeg');
                let file = dataURItoFile(image_data_url, 'image.jpg');
                let postData = new FormData();
                postData.append('description', des);
                postData.append('file', file);
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
                            // $('.hide_me').css('display', 'none');
                            // $('.carousel').removeClass('hide_cara').slick(
                            //     'slickAdd',
                            //     '<div><img src="' + data.doc_url +
                            //     '" style="width:40%"  /><a href="javascript:;" class="text-danger delete_this_file delete-btn" doc_name="' +
                            //     data.doc_name + '" doc_id="' + data.doc_id +
                            //     '" class="delete-image"><i class="fa fa-trash text-danger"></i></a></div>'
                            // );
                            // $('#video').css('display','none')
                            // $("#click-photo").css('display','none')
                            console.log(image_data_url);
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
