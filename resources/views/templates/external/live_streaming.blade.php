@extends('layouts.master')

@section('title')
Main stream
@endsection

@section('content')

@php
use \App\Http\Controllers\PickupController;
@endphp
<!-- CONTAINER -->
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('tenant/channel/list')}}">Channel-list</a></li>
                <li class="breadcrumb-item active" aria-current="page">Main stream</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- ROW-1 -->
    <div class="container">
        <div class="row chat-row">
            <div class="col-12 mb-3">
                Camera {{$streamId}} | <small class="badge bg-success">streaming</small>
            </div>
            <div class="row justify-content-center align-items-center g-2">
                <div class="col-12">
                    <iframe src="http://35.171.194.142:2000/streaming/{{$streamId}}" style="width: 100%;min-height:450px;" title="Main stream"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- ROW-1 END -->

</div>
<!-- CONTAINER END -->

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="https://cdn.socket.io/4.0.1/socket.io.min.js" integrity="sha384-LzhRnpGmQP+lOvWruF/lgkcqD+WDVt9fU3H4BWmwP5u5LTmkUGafMcpZKNObVMLU" crossorigin="anonymous"></script>

<script>
    // $(function() {
    //     let ip_address = '127.0.0.1';
    //     let socket_port = '3000';
    //     let socket = io(ip_address + ':' + socket_port);

    //     let chatInput = $('#chatInput');

    //     chatInput.keypress(function(e) {
    //         let message = $(this).html();
    //         console.log(message);
    //         if (e.which === 13 && !e.shiftKey) {
    //             socket.emit('sendChatToServer', message);
    //             chatInput.html('');
    //             return false;
    //         }
    //     });

    //     socket.on('sendChatToClient', (message) => {
    //         console.log('message received')
    //         $('.chat-content-ul').append(`<li>${message}</li>`);
    //     });
    // });
</script>
@endsection