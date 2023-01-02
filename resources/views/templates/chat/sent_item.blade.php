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
                <li class="breadcrumb-item active" aria-current="page">sent</li>

            </ol>
        </div>
    </div>
    <div class="row">
        @include('templates.chat.sidebar')
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mail Sent</h3>
                </div>
                @if(isset($chat))

                <?php
                $sender = App\Models\User::where('id', $chat->sender_id)->first();
                $receiver = App\Models\User::where('id', $chat->receiver_id)->first();


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
            $.ajax({
                url: '/api/message/reply',
                type: "POST",
                data: new FormData(this),
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
                        $('.reply_box').css('display', 'none');
                        $(".btnDiv").css('display', 'block');

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