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
                <li class="breadcrumb-item active" aria-current="page">Inbox</li>

            </ol>
        </div>
    </div>
    <div class="row">
        @include('templates.chat.sidebar')
        <div class="col-xl-9 col-lg-9 col-md-9 col-sm-12">
            <div class="card">
                <div class="card-body p-6">
                    <div class="inbox-body">
                        <div class="mail-option">
                            <div class="chk-all">
                                <div class="btn-group">
                                    <a data-bs-toggle="dropdown" href="javascript:void(0)" class="btn mini all" aria-expanded="false">
                                        All
                                        <i class="fa fa-angle-down "></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-start">
                                        <li><a href="/message/inbox?query=read"> Read</a></li>
                                        <li><a href="/message/inbox?query=unread"> Unread</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="btn-group">
                                <a href="/message/inbox" class="btn mini tooltips">
                                    <i class=" fa fa-refresh"></i>
                                </a>
                            </div>
  

                        </div>
                        <div class="table-responsive">
                            <table class="table table-inbox table-hover text-nowrap mb-0">
                                <tbody>
                                    @if(isset($chats) && sizeof($chats)>0)
                                        @foreach($chats as $chat)
                                            <?php
                                            $sender = App\Models\User::where('id', $chat->sender_id)->first();
                                            $encrypted = Illuminate\Support\Facades\Crypt::encryptString($chat->id);
                                            $first = App\Models\Chat::where('conversation_id',$chat->id)->orderBy('id','ASC')->first();
                                            ?>

                                            <tr>
                                                <td class="inbox-small-cells">
                                                    <label class="custom-control custom-checkbox mb-0 ms-3">
                                                        <input type="checkbox" class="custom-control-input" name="example-checkbox2" value="option2">
                                                        <span class="custom-control-label"></span>
                                                    </label>
                                                </td>
                                                <td class="inbox-small-cells"><i class="fa fa-star inbox-started"></i></td>
                                                <td class="view-message dont-show fw-semibold clickable-row" data-href='/inbox/{{$encrypted}}'>{{ucwords($sender->first_name)}} {{ucwords($sender->last_name)}}</td>
                                                                                        <td class="view-message clickable-row" data-href='/inbox/{{$encrypted}}'>{{empty($first) ? '': $first->subject}}</td>

                                                <td class="view-message text-end fw-semibold clickable-row" data-href='/inbox/{{$encrypted}}'>{{$chat->created_at->diffForHumans()}}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            {!! $chats->links() !!}
        </div>

    </div>

</div>
@endsection

@section('bottom-script')

<!--<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>-->
<script src="https://reservations.campinspectors.com/assets/js/table-data.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/jszip.min.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/buttons.html5.min.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/buttons.print.min.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/buttons.colVis.min.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/dataTables.responsive.min.js"></script>
<script src="https://reservations.campinspectors.com/assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
<script src="https://reservations.campinspectors.com/assets/js/table-data.js"></script>
<script>
    $(document).ready(function() {

        $(document).on('submit', '#message_form', function(e) {

            e.preventDefault();
            $.ajax({
                url: '/api/message/send',
                type: "POST",
                data: new FormData(this),
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