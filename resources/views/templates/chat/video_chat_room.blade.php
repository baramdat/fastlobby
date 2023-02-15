@extends('layouts.master')

@section('title')
Chat
@endsection

@section('content')
<!-- Styles -->
<style>
    html,
    body {
        background-color: #fff;
        color: #636b6f;
        font-family: 'Raleway', sans-serif;
        font-weight: 100;
        height: 100vh;
        margin: 0;
    }

    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
    }

    .links>a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 30px;
    }

    #my-video-chat-window video {
        max-width: 350px;
    }

    #remote-media-div video {
        height: 150px;
    }

    .fe-phone-off:before {
        background: #d64040 !important;
        color: white !important;
        margin: 0px !important;
        padding: 7px !important;
        border-radius: 50% !important;
        font-size: 20px !important;
    }
</style>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
    <div class="top-right links">
        @auth
        <a href="{{ url()->previous() }}">Back</a>
        @else
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
        @endauth
    </div>
    @endif

    <div class="content">
        <!--<div class="title m-b-md">-->
        <!--    Join With Ruban!-->
        <!--</div>-->


        <div class="row">
            <div class="col-lg-8">
                <div id="media-div" class="d-none">
                </div>
                <div id="my-video-chat-window" class="mb-2">

                </div>
            </div>
            <div class="col-lg-4">
                <div id="remote-media-div" style="max-height: 150px;">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="d-flex justify-content-center">
                <a id="unmuteAudio" href="javascript:void(0)" class="fe fe-mic-off text-center" style="padding:10px;border-radius:50%; font-size:30px;display:none;"></a>
                <a id="muteAudio" href="javascript:void(0)" class="fe fe-mic text-center " style="padding:10px;border-radius:50%; font-size:30px;"></a>
                <a id="videoShow" href="javascript:void(0)" class="fe fe-video-off text-center " style="padding:10px;border-radius:50%; font-size:30px;display:none;"></a>
                <a id="videoHide" href="javascript:void(0)" class="fe fe-video text-center " style="padding:10px;border-radius:50%; font-size:30px;"></a>
                <a id="callDrop" data="{{$user_one}}" data-id="{{$user_two}}" href="javascript:void(0)" class="fe fe-phone-off text-center " style="padding:7px;border-radius:50%; font-size:30px;"></a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom-script')

<script src="https://sdk.twilio.com/js/video/releases/2.17.1/twilio-video.min.js"></script>
<script>
    $('#my-video-chat-window').ready(function() {
        console.log('functions hits');
        $('#loading').css('display', 'none');
    });

    var room = {}
    // var room_arr = JSON.parse('{{json_encode($room)}}')
    // console.log(room_arr)
    Twilio.Video.createLocalTracks({
        audio: true,
        video: {
            width: 500
        }
    }).then(function(localTracks) {

        return Twilio.Video.connect('{{ $accessToken }}', {
            name: '{{ $roomName }}',
            tracks: localTracks,
            video: {
                width: 500
            }
        });
    }).then(function(room) {
        room = room
        console.log('Successfully joined a Room: ', room);
        const videoChatWindow = document.getElementById('my-video-chat-window');
        room.participants.forEach(participantConnected);
        Twilio.Video.createLocalVideoTrack().then(track => {
            videoChatWindow.appendChild(track.attach());
        });
        var previewContainer = document.getElementById(room.localParticipant.sid);
        console.log({
            previewContainer
        });
        if (!previewContainer || !previewContainer.querySelector('video')) {
            // alert("preview")
            participantConnected(room.localParticipant);
        }

        room.on('participantConnected', function(participant) {
            // alert('coming');
            console.log("Joining: ", participant.identity);
            console.log(`Participant "${participant.identity}" connected`);

            participant.tracks.forEach(publication => {
                if (publication.isSubscribed) {
                    const track = publication.track;
                    document.getElementById('remote-media-div').appendChild(track.attach());
                }
            });

            participant.on('trackSubscribed', track => {
                document.getElementById('remote-media-div').appendChild(track.attach());
            });
        });


        room.on('participantDisconnected', function(participant) {
            console.log("Disconnected: ", participant.identity);
            participantDisconnected(participant);
        });



        room.participants.forEach(participant => {
            participant.tracks.forEach(publication => {
                if (publication.track) {
                    document.getElementById('remote-media-div').appendChild(publication.track.attach());
                }
            });
            participant.on('trackSubscribed', track => {
                document.getElementById('remote-media-div').appendChild(track.attach());
            });
        });
        // muteVoice();
        $(document).on('click', '#muteAudio', function() {
            muteVoice()
            $('#unmuteAudio').css('display', 'block');
            $('#muteAudio').css('display', 'none');
        });
        //unmute voice
        $(document).on('click', '#unmuteAudio', function() {
            unmuteVoice();
            $('#muteAudio').css('display', 'block');
            $('#unmuteAudio').css('display', 'none');
        });
        //video hide
        $(document).on('click', '#videoHide', function() {
            videoHide()
            $('#videoShow').css('display', 'block');
            $('#videoHide').css('display', 'none');
        });
        //unhide video
        $(document).on('click', '#videoShow', function() {
            videoShow();
            $('#videoHide').css('display', 'block');
            $('#videoShow').css('display', 'none');
        });
        //call end

        $(document).on('click', '#callDrop', function() {
            var user_one = $(this).attr('data');
            var user_two = $(this).attr('data-id');
            if (room.disconnect()) {
                $.ajax({
                    type: "POST",
                    url: "/api/chat_status/change",
                    data: {
                        user_one: user_one,
                        user_two: user_two
                    },
                    dataType: "JSON",
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        if (response["status"] == "fail") {
                            alert(response["msg"]);
                        } else if (response["status"] == "success") {
                            console.log('disconnected');
                            $('#remote-media-div').html('');
                            // location.replace('https://www.fastlobby.com/video/chat/compose');
                        }
                    }
                });
                updateUserRoomStatus('left')

            }
            room.on('disconnected', room => {
                // Detach the local media elements
                room.localParticipant.tracks.forEach(publication => {
                    const attachedElements = publication.track.detach();
                    attachedElements.forEach(element => element.remove());
                });
            });

        });


        //mute voice
        function muteVoice() {
            room.localParticipant.audioTracks.forEach(publication => {
                console.log(publication, 'muted')
                publication.track.disable();
            });

        }
        //unmute audio
        function unmuteVoice() {
            room.localParticipant.audioTracks.forEach(publication => {
                publication.track.enable();
            });
        }
        //hide video
        function videoHide() {
            room.localParticipant.videoTracks.forEach(publication => {
                console.log(publication, 'video disabled');
                publication.track.disable();
            });
        }
        //unhide video
        function videoShow() {
            room.localParticipant.videoTracks.forEach(publication => {
                publication.track.enable();
            });
        }


    });
    // additional functions will be added after this point

    function participantConnected(participant) {
        // alert(participant.sid);
        console.log('Participant "%s" connected', participant.identity);

        const div = document.createElement('div');
        div.id = participant.sid;
        div.setAttribute("style", "float: left; margin: 10px;");
        div.innerHTML = "<div style='clear:both'>" + participant.identity + "</div>";

        participant.tracks.forEach(function(track) {
            if (track.isSubscribed) {
                // trackAdded(div, track)
                document.getElementById('remote-media-div').appendChild(track.attach());
            }
        });

        participant.on('trackAdded', function(track) {
            if (track.isSubscribed) {
                // trackAdded(div, track)
                document.getElementById('remote-media-div').appendChild(track.attach());
            }
        });
        participant.on('trackRemoved', trackRemoved);

        document.getElementById('media-div').appendChild(div);
        updateUserRoomStatus('joined')
    }

    function participantDisconnected(participant) {
        console.log('Participant "%s" disconnected', participant.identity);
        updateUserRoomStatus('left')
        $('#remote-media-div').html('');
        // location.replace('https://www.fastlobby.com/video/chat/compose');
    }


    function trackAdded(div, track) {
        div.appendChild(track.attach());
        var video = div.getElementsByTagName("video")[0];
        if (video) {
            video.setAttribute("style", "max-width:300px;");
        }
    }

    function trackRemoved(track) {
        // track.detach().forEach(function(element) {
        //     element.remove()
        // });
    }

    function updateUserRoomStatus(status){
        var auth_user_id = "{{Auth::id()}}"
        // var user_id = auth_user_id == room_arr.user_one ? room_arr.user_two : room_arr.user_one
        $.ajax({
            url: '/api/video/chat/room/user/status',
            type: "post",
            dataType: "JSON",
            data: {user_id: auth_user_id,status:status,room: '{{$roomName}}'},
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
                console.log(response)
                // if (response["status"] == "success") {
                //     $("#tenant").html(response['data']);
                // } else if (response["status"] == "fail") {

                // }
            },
            error: function(error) {
                //console.log(error);
            }
        });
    }
</script>

@endsection