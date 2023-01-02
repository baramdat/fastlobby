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
        <div class="col-lg-7 col-xl-7">
            <h4>Chat history</h4>
            <div class="table-responsive">
                <table class="table text-sm-nowrap table-hover mb-0" id="dataTable">
                    <thead>
                        <tr>
                            <th>Sender Name</th>
                            <th>Subject</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody id="tBody">

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-1 col-xl-1"></div>
        <div class="col-lg-4 col-xl-4 mb-2" style="box-shadow: 0px 0px 2px rgb(0,0,0.1); padding:15px; border-radius:5px">
            <h4 class="text-center"> Send message</h4>
            <form id="msg_form">
                <div class="mb-3 mt-3">
                    <label for="email">Subject:</label>
                    <input type="text" class="form-control" id="text" placeholder="Your subject" name="subject">
                </div>
                <div class="mb-3">
                    <label for="pwd">Select User:</label>
                    <select class="form-control form-select" id="receiver_id" name="receiver_id" required>
                        @if(isset($users) && sizeof($users)>0)
                        @foreach($users as $user)
                        <option value="{{$user->id}}">{{ucwords($user->first_name)}} {{ucwords($user->last_name)}} ({{$user->roles->pluck('name')[0]}})</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-check mb-3 mt-3">
                    <label for="comment">Message:</label>
                    <textarea class="form-control" rows="5" id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100" id="btnSubmit">
                    <i class="fa fa-spinner fa-pulse" style="display: none;"></i>
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('bottom-script')

      <!--<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>-->
  <script src="https://reservations.campinspectors.com/assets/js/table-data.js"></script>
  <script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/jquery.dataTables.min.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/dataTables.buttons.min.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/jszip.min.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/pdfmake/pdfmake.min.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/pdfmake/vfs_fonts.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/buttons.html5.min.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/buttons.print.min.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/js/buttons.colVis.min.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/dataTables.responsive.min.js"></script> <script src="https://reservations.campinspectors.com/assets/plugins/datatable/responsive.bootstrap5.min.js"></script> <script src="https://reservations.campinspectors.com/assets/js/table-data.js"></script>
<script>
    $(document).ready(function() {

        $(document).on('click', '#btnSubmit', function() {

            $("#msg_form").submit();

        });

        $(document).on('submit', '#msg_form', function(e) {

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
                    $(".fa-pulse").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-pulse").css('display', 'none');
                },
                success: function(response) {
                    console.log(response)
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        $("#msg_form")[0].reset();
                    }
                },
                error: function(error) {
                    console.log(error);
                },
                async: false
            });
        })

        $(function() {
            setInterval(messages, 5000);
        });
       
        function messages() {
            pageInfo = table.page.info();
            $.ajax({
                url: '/api/messages/get',
                type: "get",
                dataType: "JSON",
                cache: false,
                beforeSend: function() {},
                complete: function() {},
                success: function(response) {
                    console.log(response)
                    if (response["status"] == "fail") {


                    } else if (response["status"] == "success") {
                        // $("#tBody").html(response["html"])
                        datatable(response["html"])

                    }
                },
                error: function(error) {
                    console.log(error);
                },
                async: false
            });
        }

        var table = $('#dataTable').DataTable({

        "oLanguage": {
            "oPaginate": {
                "sPrevious": "Prev"
            },
        },
        });
        var pageInfo = table.page.info();

        function datatable(rows) {
            $("#dataTable tbody").empty();
            $("#dataTable").DataTable().clear();
            $("#dataTable").DataTable().destroy();
            $("#tBody tr").remove();
            $("#dataTable tbody").empty();
            $("#tBody").html(rows);

            table = $("#dataTable").DataTable({

                "pageLength": 10,
                "oLanguage": {

                    "oPaginate": {
                        "sPrevious": "Prev"
                    },
                },
                "columnDefs": [{
             
                }]
            });
            var page = table.page.info().pages <= pageInfo.page ? pageInfo.page - 1 : pageInfo.page
            table.page(page).draw(false)
        }


    });
</script>
@endsection