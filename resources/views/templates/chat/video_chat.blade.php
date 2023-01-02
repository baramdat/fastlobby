@extends('layouts.master')

@section('title')
Video chat
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7">
            <div class="content" style="box-shadow:1px 1px 5px 1px rgba(0,0,0);padding:10px;margin-top:10vh; border-radius:2px">
                <div class="title m-b-md text-center">
                    Video Chat Rooms
                </div>
                {!! Form::open(['url' => 'room/create']) !!}
                {!! Form::label('roomName', 'Create or Join a Video Chat Room') !!}
                {!! Form::text('roomName') !!}
                {!! Form::submit('Go') !!}
                {!! Form::close() !!}
                @if($rooms)
                <br>
                <p><strong>Available Rooms:</strong></p>
                @foreach ($rooms as $room)
                <a href="{{ url('/room/join/'.$room) }}" class="btn btn-primary btn-sm">
                    {{ ucwords($room) }}
                </a>&nbsp;
                @endforeach
                @endif
            </div>
        </div>
        <div class="col-lg-6"></div>
    </div>
</div>

@endsection

@section('bottom-script')
<script>
    $(document).ready(function() {
        $("#roomName").attr("required", "true");
    });
</script>


@endsection