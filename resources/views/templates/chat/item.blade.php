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
            <div class="card" id="chat">
                <div class="card-header">
                    <h3 class="card-title">Mail Read{{$receiverId}}</h3>
                </div>
                @if(isset($chats) && sizeof($chats)>0)
                <?php
                $last_key = count($chats);
                $sender_id = Auth::user()->id;
                $receiver_idd = $receiverId;
                $conversation_id = $conversationId;
                ?>
                @foreach($chats as $key=>$chat)

                <?php
                $sender = App\Models\User::where('id', $chat->sender_id)->first();
                $receiver = App\Models\User::where('id', $chat->receiver_id)->first();
                $i = $key + 1;

                if($chat->file !=''){

                    $files = json_decode($chat->file,true);

                }else{

                    $files = [];
                }
                ?>
                <div class="card-body">
                    <div class="email-media" id="row{{$chat->id}}">
                        <div class="mt-0 d-sm-flex">
                            @if($sender->image=="")
                            <img class="me-2 rounded-circle avatar avatar-lg" src="{{asset('assets/images/users/6.jpg')}}" alt="avatar">
                            @else
                            <img class="me-2 rounded-circle avatar avatar-lg" src="{{asset('uploads/files/'.$sender->image)}}" alt="avatar">
                            @endif

                            <div class="media-body pt-0">
                                <div class="float-md-end d-flex fs-15">
                                    <small class="me-3 mt-3 text-muted">{{$chat->created_at->diffForHumans()}}</small>
                                </div>
                                <div class="media-title text-dark font-weight-semibold mt-1">{{ucwords($sender->first_name)}} {{ucwords($sender->last_name)}}<span class="text-muted font-weight-semibold">( {{$sender->email}} )</span></div>
                                <small class="mb-0">to {{ucwords($receiver->first_name)}} {{ucwords($receiver->last_name)}} ( {{$receiver->email}}) </small>

                            </div>

                        </div>
                        <div class="eamil-body mt-5">
                            {!! $chat->message !!}
                        </div>

                        @if(isset($files) && sizeof($files)>0)

                        <?php
                            $fileCount = count($files);
                            $imgExt = ['jpg', 'png', 'JPG', 'PNG', 'jpeg', 'svg'];
                        ?>
                        <hr>
                        <div class="email-attch">
                            <p class="font-weight-semibold">{{$fileCount}} Attachments <a href="filemanager-details.html">View</a></p>
                        </div>
                        <div class="row attachments-doc">

                            @foreach($files as $file)

                            
                            <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 mb-2 mb-sm-0">
                                <div class="border overflow-hidden p-0 br-7">
                                    <a href="{{asset('uploads/files/'.$file['filename'])}}" target="_blank">
                                        @if(in_array($file["ext"],$imgExt))
                                        <img src="{{asset('uploads/files/'.$file['filename'])}}" class="card-img-top" alt="img">
                                        <!-- <img src="../assets/images/media/8.jpg" class="card-img-top" alt="img"> -->
                                        @endif
                                    </a>
                                    <div class="p-3 text-center">
                                        <a href="{{asset('uploads/files/'.$file['filename'])}}" target="_blank" class="fw-semibold fs-15 text-dark text-truncate" style="max-width:100px ;">{{$file["name"]}}</a>
                                        <p class="text-muted.ms-2 mb-0 fs-11">({{$file["size"]}} KB)</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @if($i==$last_key)
                <div class="card-footer btnDiv">
                    <a class="btn btn-primary mt-1 mb-1 btnReply" href="javascript:void(0)" id="{{$chat->id}}"><i class="fa fa-reply"></i> Reply</a>
                </div>
                <div class="reply_box shadow mb-3 mt-2" style="display: none;">
                    <form id="reply_form">
                        <div class="col-12">
                            <textarea class="form-control" name="message" id="summernote"></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="row">
                                <div class="col-2">
                                    <input type="hidden" name="message_id" id="message_id" value="{{$chat->id}}">
                                    <input type="hidden" name="sender_id" value="{{$sender_id}}">
                                    <input type="hidden" name="receiver_id" value="{{$receiverId}}">
                                    <input type="hidden" name="conversation_id" value="{{$conversation_id}}">
                                    <button type="submit" class="btn btn-primary ml-2 mb-2" id="btnSubmi"><i class="ion ion-ios-paper-plane"></i><i class="fas fa-spinner fa-spin fa-reply" style="display:none"></i>&nbsp; Send</button>
                                </div>
                                <div class="col-4">
                                    <div class="mb-2">
                                        <a href="javascript:void(0)" class="btn btn-icon btn-white btn-svg" id="btnUp" data-bs-toggle="tooltip" title="" data-bs-original-title="Attach"><span class="ri-attachment-2"></span></a>
                                    </div>
                                </div>
                                <div class="col-6 text-end mt-2">
                                    <a href="javascript:;" class="btnClose"><i class="fas fa-trash"></i></a>
                                </div>
                            </div>
                            <div class="row" id="uploadFilesDiv" style="padding-left:20px">

                            </div>
                            <div id="messageMsg" style="padding-left:20px;margin-top:5px;padding-bottom:10px;"></div>
                        </div>
                    </form>
                    <input type="file" name="attachments[]" id="attachments" style="opacity: 0;">

                </div>
                @endif
                @endforeach
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@section('bottom-script')

<script>
    $(document).ready(function() {

        $('#summernote').summernote({
            height: 200,
            focus: true
        });

        //start attchment portion
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
        //end attchment portion

        $(document).on('click', '.btnReply', function() {

            $('.reply_box').css('display', 'block');
            $(".btnDiv").css('display', 'none');

        });

        $(document).on('click', '.btnClose', function() {

            $('.reply_box').css('display', 'none');
            $(".btnDiv").css('display', 'block');

        });

        $(document).on('submit', '#reply_form', function(e) {
            var id = $("#message_id").val();
            e.preventDefault();
            
            var attachments = JSON.stringify(files);
            var formData = new FormData(this);
            formData.append('attach', attachments);
            
            $.ajax({
                url: '/api/message/reply',
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmi").attr('disabled', true);
                    $(".fa-reply").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmi").attr('disabled', false);
                    $(".fa-reply").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        $('#summernote').summernote('reset');
                        $(response["html"]).insertAfter('#row' + id);
                        $("#message_id").val(response["id"]);
                        $("#receiverId").val(response['receiverId']);
                        $('.reply_box').css('display', 'none');
                        $(".btnDiv").css('display', 'block');
                        // $("#chat").load();
                        $("#chat").load(location.href+" #chat>*","");
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });


    });
</script>
@endsection