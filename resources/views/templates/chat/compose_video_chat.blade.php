@extends('layouts.master')

@section('title')
Video chat
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3"> </div>
        <div class="col-lg-6">
            <h3 class="text-center mt-3">Select Users For Chat</h3>
            @if(isset($error))
                <div class="alert alert-danger" role="alert">
                    <b>Error:</b> "{{$error}}", please contact admin.
                </div>
            @endif
            <div class="card">
                <ul class="list-group list-group-flush">
                    @if(isset($users) && sizeof($users) > 0)
                    @foreach($users as $user)
                    <?php
                    if ($user->image == NULL) {
                        $img = "assets/images/users/avatar.jpg";
                    } else {
                        $img = '/uploads/files/' . $user->image;
                    }
                    ?>
                    <div class="row p-2">
                        <div class="col-lg-8">
                            <li class="list-group-item"><span><img src="{{asset($img)}}" id="user_two" style="width:40px;height:40px;border-radius:50%;"></span> {{ucwords($user->first_name)}} {{ucwords($user->last_name)}} ( {{$user->roles->pluck('name')->first()}} )</li>
                        </div>
                        <div class="col-lg-4 text-center pt-5"><button class="btn btn-primary btn-sm" data-id="{{$user->id}}" id="btnSubmit"><i class="fa fa-spinner fa-pulse" style="display: none;"></i> Start Chat</button></div>
                    </div>
                    @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <div class="col-lg-3">
        </div>
    </div>
</div>


@endsection

@section('bottom-script')
<script>
    $(document).ready(function() {
        $('#roomName').attr('required');
        $(document).on('click', '#btnSubmit', function() {
            var id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: "/api/create/specific/room/" + id,
                data: {
                    id: id
                },
                dataType: "JSON",
                cache: false,
                success: function(response) {
                    if (response["status"] == "fail") {
                        alert(response["msg"]);
                    } else if (response["status"] == "success") {
                        window.location.href = response["url"];
                    }
                }
            });
        });
    });
</script>
@endsection