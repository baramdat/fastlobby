@extends('layouts.master')

@section('title')
Chat
@endsection

@section('content')

<link href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<style>
    .card-bordered {
        border: 1px solid #ebebeb;
    }

    .card {
        border: 0;
        border-radius: 0px;
        margin-bottom: 30px;
        -webkit-box-shadow: 0 2px 3px rgba(0, 0, 0, 0.03);
        box-shadow: 0 2px 3px rgba(0, 0, 0, 0.03);
        -webkit-transition: .5s;
        transition: .5s;
    }

    .padding {
        padding: 3rem !important
    }

    body {
        background-color: #f9f9fa
    }

    .card-header:first-child {
        border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
    }


    .card-header {
        display: -webkit-box;
        display: flex;
        -webkit-box-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        align-items: center;
        padding: 15px 20px;
        background-color: transparent;
        border-bottom: 1px solid rgba(77, 82, 89, 0.07);
    }

    .card-header .card-title {
        padding: 0;
        border: none;
    }

    h4.card-title {
        font-size: 17px;
    }

    .card-header>*:last-child {
        margin-right: 0;
    }

    .card-header>* {
        margin-left: 8px;
        margin-right: 8px;
    }

    .btn-secondary {
        color: #4d5259 !important;
        background-color: #e4e7ea;
        border-color: #e4e7ea;
        color: #fff;
    }

    .btn-xs {
        font-size: 11px;
        padding: 2px 8px;
        line-height: 18px;
    }

    .btn-xs:hover {
        color: #fff !important;
    }




    .card-title {
        font-family: Roboto, sans-serif;
        font-weight: 300;
        line-height: 1.5;
        margin-bottom: 0;
        padding: 15px 20px;
        border-bottom: 1px solid rgba(77, 82, 89, 0.07);
    }


    .ps-container {
        position: relative;
    }

    .ps-container {
        -ms-touch-action: auto;
        touch-action: auto;
        overflow: hidden !important;
        -ms-overflow-style: none;
    }

    .media-chat {
        padding-right: 64px;
        margin-bottom: 0;
    }

    .media {
        padding: 16px 12px;
        -webkit-transition: background-color .2s linear;
        transition: background-color .2s linear;
    }

    .media .avatar {
        flex-shrink: 0;
    }

    .avatar {
        position: relative;
        display: inline-block;
        width: 36px;
        height: 36px;
        line-height: 36px;
        text-align: center;
        border-radius: 100%;
        background-color: #f5f6f7;
        color: #8b95a5;
        text-transform: uppercase;
    }

    .media-chat .media-body {
        -webkit-box-flex: initial;
        flex: initial;
        display: table;
    }

    .media-body {
        min-width: 0;
    }

    .media-chat .media-body p {
        position: relative;
        padding: 6px 8px;
        margin: 4px 0;
        background-color: #f5f6f7;
        border-radius: 3px;
        font-weight: 100;
        color: #9b9b9b;
    }

    .media>* {
        margin: 0 8px;
    }

    .media-chat .media-body p.meta {
        background-color: transparent !important;
        padding: 0;
        opacity: .8;
    }

    .media-meta-day {
        -webkit-box-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        align-items: center;
        margin-bottom: 0;
        color: #8b95a5;
        opacity: .8;
        font-weight: 400;
    }

    .media {
        padding: 16px 12px;
        -webkit-transition: background-color .2s linear;
        transition: background-color .2s linear;
    }

    .media-meta-day::before {
        margin-right: 16px;
    }

    .media-meta-day::before,
    .media-meta-day::after {
        content: '';
        -webkit-box-flex: 1;
        flex: 1 1;
        border-top: 1px solid #ebebeb;
    }

    .media-meta-day::after {
        content: '';
        -webkit-box-flex: 1;
        flex: 1 1;
        border-top: 1px solid #ebebeb;
    }

    .media-meta-day::after {
        margin-left: 16px;
    }

    .media-chat.media-chat-reverse {
        padding-right: 12px;
        padding-left: 64px;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: reverse;
        flex-direction: row-reverse;
    }

    .media-chat {
        padding-right: 64px;
        margin-bottom: 0;
    }

    .media {
        padding: 16px 12px;
        -webkit-transition: background-color .2s linear;
        transition: background-color .2s linear;
    }

    .media-chat.media-chat-reverse .media-body p {
        float: right;
        clear: right;
        background-color: #48b0f7;
        color: #fff;
    }

    .media-chat .media-body p {
        position: relative;
        padding: 6px 8px;
        margin: 4px 0;
        background-color: #f5f6f7;
        border-radius: 3px;
    }


    .border-light {
        border-color: #f1f2f3 !important;
    }

    .bt-1 {
        border-top: 1px solid #ebebeb !important;
    }

    .publisher {
        position: relative;
        display: -webkit-box;
        display: flex;
        -webkit-box-align: center;
        align-items: center;
        padding: 12px 20px;
        background-color: #f9fafb;
    }

    .publisher>*:first-child {
        margin-left: 0;
    }

    .publisher>* {
        margin: 0 8px;
    }

    .publisher-input {
        -webkit-box-flex: 1;
        flex-grow: 1;
        border: none;
        outline: none !important;
        background-color: transparent;
    }

    button,
    input,
    optgroup,
    select,
    textarea {
        font-family: Roboto, sans-serif;
        font-weight: 300;
    }

    .publisher-btn {
        background-color: transparent;
        border: none;
        color: #8b95a5;
        font-size: 16px;
        cursor: pointer;
        overflow: -moz-hidden-unscrollable;
        -webkit-transition: .2s linear;
        transition: .2s linear;
    }

    .file-group {
        position: relative;
        overflow: hidden;
    }

    .publisher-btn {
        background-color: transparent;
        border: none;
        color: #cac7c7;
        font-size: 16px;
        cursor: pointer;
        overflow: -moz-hidden-unscrollable;
        -webkit-transition: .2s linear;
        transition: .2s linear;
    }

    .file-group input[type="file"] {
        position: absolute;
        opacity: 0;
        z-index: -1;
        width: 20px;
    }

    .text-info {
        color: #48b0f7 !important;
    }
</style>
<div class="main-container container-fluid">

    <div class="page-header">
        <h1 class="page-title">Chat</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Chat</li>

            </ol>
        </div>
    </div>
    <div class="row">
        @include('templates.chat.sidebar')
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Compose new message</h3>
                    {{$current_role}}
                </div>
                <form id="message_form">
                    <div class="card-body">

                        <div class="form-group">
                            <div class="row align-items-center">
                                <label class="col-xl-2 form-label">To</label>
                                <div class="col-xl-10">

                                    <select class="form-control form-select" id="receiver_id" name="receiver_id" required>
                                        <option value="" selected>Select User</option>
                                        @if(isset($users) && sizeof($users)>0)
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}">{{ucwords($user->first_name)}} {{ucwords($user->last_name)}} ({{$user->roles->pluck('name')[0]}})</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row align-items-center">
                                <label class="col-xl-2 form-label">Subject</label>
                                <div class="col-xl-10">
                                    <input type="text" class="form-control" name="subject" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row ">
                                <label class="col-xl-2 form-label">Message</label>
                                <div class="col-xl-10">
                                    <textarea class="form-control" name="message" id="summernote"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer d-sm-flex">
                        <div class="mt-2 mb-2">
                            <a href="javascript:void(0)" class="btn btn-icon btn-white btn-svg" id="btnUp" data-bs-toggle="tooltip" title="" data-bs-original-title="Attach"><span class="ri-attachment-2"></span></a>
                        </div>

                        <div class="btn-list ms-auto my-auto">
                            <button type="button" class="btn btn-danger btn-space mb-0">Cancel</button>
                            <button type="submit" id="btnSubmit" class="btn btn-primary btn-space mb-0">
                                <i class="fas fa-spinner fa-spin" style="display:none"></i> Send message
                            </button>
                        </div>
                    </div>
                    <div class="row" id="uploadFilesDiv"  style="padding-left:20px">

                    </div>
                    <div id="messageMsg"  style="padding-left:20px;margin-top:5px;padding-bottom:10px;"></div>
                </form>
            </div>
        </div>
    </div>

</div>
<input type="file" name="attachments[]" id="attachments" style="opacity: 0;">

@endsection

@section('bottom-script')
<script>
    $(document).ready(function() {
        $('#summernote').summernote();

        $(document).on('click', '#btnUp', function() {
            $("#attachments").click();
            console.log('click')
        })

        var fileCount = 0;
        var files = [];

        function uploadFileListHTML(filesize, filename) {
            var html = '<div id="row' + fileCount + '" class="row text-right"><div class="col-lg-12">';
            html += '<span class="fileName' + fileCount + '">' + filename + '(' + formatBytes(filesize) + ')&nbsp;</span>';
            html += '<input id="files' + fileCount + '" name="files[]" type="hidden">';
            //html += '<span><small class="fileMsg'+fileCount+'"> file uploading.. 20%</small></span>';
            html += '<span><small id="' + fileCount + '" class="filedelBtn' + fileCount + '"><a href="javascript:;" class="fileDelete" id="' + fileCount + '">&nbsp;&nbsp;<i class="fa fa-trash text-danger border-left"></i></a></small></span>';
            html += '</div></div>';
            $("#uploadFilesDiv").append(html);
            fileCount++;
            return html;
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function messageMsg(msg) {
            $("#messageMsg").css('opacity', 1);
            $("#messageMsg").html(msg)
            setTimeout(function() {
                $("#messageMsg").css('opacity', 0);
            }, 10000);
        }
        $('#attachments').on('change', function() {

            $("#btnSubmit").attr('disabled', true);
            var filesize = this.files[0].size;
            var filename = $(this).val().replace(/.*(\/|\\)/, '');
            var token = '{{ csrf_token() }}';
            $("#fileName").html(filename + '(' + filesize + ')');
            // output raw value of file input
            $('#filename').html($(this).val().replace(/.*(\/|\\)/, ''));
            // or, manipulate it further with regex etc.
            var filename = $(this).val().replace(/.*(\/|\\)/, '');
            // .. do your magic
            $('#filename').html(filename);
            var formData = new FormData();
            formData.append('file', this.files[0]);
            formData.append('fileCount', fileCount);
            formData.append('_token', token);
            //upload file
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            //Do something with upload progress here
                            //$("#messageMsg").html('File Upload.. '+parseFloat(percentComplete).toFixed(2))
                            messageMsg('File Upload.. ' + parseFloat(percentComplete).toFixed(2) + '%')
                        }
                    }, false);
                    return xhr;
                },

                type: 'POST',
                url: '/file/upload',
                data: formData,
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set x   
                success: function(data) {
                    console.log(data)
                    //Do something on success
                    files.push(data["file"]);
                    $("#uploadFilesDiv").append(data["file"]["html"]);
                    fileCount++;
                    messageMsg(data["msg"])
                    $("#btnSubmit").attr('disabled', false);
                    $("#attachments").val('');
                },
                error: function(error) {
                    console.log(error)
                    $("#btnSubmit").attr('disabled', false);
                }
            });
            $('#attachments').append('<input type="file" name="attachments[]" style="opacity:0"/>')
        });
        //Delete uploaded file
        $(document).on('click', '.btnDelete', function(e) {
            $("#btnSubmit").attr('disabled', true);
            var id = $(this).attr('id');
            $("#row" + id).remove();
            var file = '';
            $.each(files, (i, v) => {
                if (v.fileCount == id) {
                    file = v.filename;
                }
            });
            //upload file
            $.ajax({
                type: 'GET',
                url: '/file/delete',
                data: {
                    file: file
                },
                dataType: "JSON",
                success: function(data) {
                    //Do something on success
                    files = files.filter(function(item) {
                        return item.fileCount !== id
                    })
                    messageMsg(data["msg"])
                    $("#btnSubmit").attr('disabled', false);
                },
                error: function(error) {
                    console.log(error)
                    $("#btnSubmit").attr('disabled', false);
                }
            });
        });



        $(document).on('submit', '#message_form', function(e) {

            e.preventDefault();

            var attachments = JSON.stringify(files);
            var formData = new FormData(this);
            formData.append('attach',attachments);

            $.ajax({
                url: '/api/message/send',
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spin").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spin").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                    $("#message_form")[0].reset();
                    $(".note-editable").empty();
                    $("#uploadFilesDiv").html(" ");

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })


    });
</script>
@endsection