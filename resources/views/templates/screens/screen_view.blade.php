<style>
    .video-container {
        position: relative;
        height: auto;
        padding-bottom: 47.25%;
        padding-top: 1.875em;
        overflow: hidden;

        background-color: black;
        background-repeat: no-repeat;
        background-size: cover;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    .fullscreen-video {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        max-width: 100%;
        max-height: 100%;

    }

    .video-container video {
        /* Make video to at least 100% wide and tall */
        min-width: 100%;
        min-height: 100%;

        /* Setting width & height to auto prevents the browser from stretching or squishing the video */
        width: auto;
        height: auto;

        /* Center the video */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .video-container img {
        /* Make video to at least 100% wide and tall */
        min-width: 100%;
        min-height: 100%;

        /* Setting width & height to auto prevents the browser from stretching or squishing the video */
        width: auto;
        height: auto;

        /* Center the video */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<div style="height: auto; width:auto;">
    <center>
        <div class="video-container">

            <video class="fullscreen-video" id="video_player" autoplay onended="run();" auto muted>

                <source id="myvideo" src="{{ asset('/uploads/files/videos') . '/' . $video }}" type="video/mp4">

            </video>
            <div class="fullscreen-video">
                <img src="" alt="" id="imageFile" style="display:none">
            </div>
           
        </div>
        <hr>
        @foreach($qr_code as $qr)
        <img src="{{ asset($qr->image) }}" height ="50px" width ="50px" alt=""> &nbsp;&nbsp; &nbsp;&nbsp;
        @endforeach
    </center>
</div>
{{-- </div> --}}
{{-- <iframe width="100%" height="100%" src="{{ asset('/uploads/files/videos') . '/' . $video->name }}" frameborder="0" allow="autoplay; encrypted-media">
        </iframe> --}}
{{-- </div>
</div> --}}

<script type="text/javascript">
    var video_list = <?php echo json_encode($array); ?>;
    var video_index = 0;
    var video_player = document.getElementById("myvideo");
    var video = document.getElementById("video_player");
    var images = document.getElementById("imageFile");
    if (video_list[video_index].toUpperCase().includes(".JPG") || video_list[video_index].toUpperCase().includes(
            ".JPEG") || video_list[video_index].toUpperCase().includes(".PNG") || video_list[video_index].toUpperCase()
        .includes(".GIF") || video_list[video_index].toUpperCase().includes(".TIFF") || video_list[video_index]
        .toUpperCase().includes(".PSD") || video_list[video_index].toUpperCase().includes(".PDF") || video_list[
            video_index].toUpperCase().includes(".EPS") || video_list[video_index].toUpperCase().includes(".AI") ||
        video_list[video_index].toUpperCase().includes(".INDD") || video_list[video_index].toUpperCase().includes(
            ".RAW")) {
        image(0);
    }
    //    run();
    function run() {

        if (video_index < video_list.length - 1) {
            video_index++;
        } else {
            video_index = 0;
        }
        //console.log(video_index + 'test');
        if (video_list[video_index].toUpperCase().includes(".JPG") || video_list[video_index].toUpperCase().includes(
                ".JPEG") || video_list[video_index].toUpperCase().includes(".PNG") || video_list[video_index]
            .toUpperCase().includes(".GIF") || video_list[video_index].toUpperCase().includes(".TIFF") || video_list[
                video_index].toUpperCase().includes(".PSD") || video_list[video_index].toUpperCase().includes(".PDF") ||
            video_list[video_index].toUpperCase().includes(".EPS") || video_list[video_index].toUpperCase().includes(
                ".AI") || video_list[video_index].toUpperCase().includes(".INDD") || video_list[video_index]
            .toUpperCase().includes(".RAW")) {
            video.pause();
            image(video_index);
        } else {
            $("#imageFile").css('display', 'none');
            $("#video_player").css('display', 'block');
            video_player.setAttribute("src", '{{ asset('/uploads/files/videos') }}/' + video_list[video_index]);
            video.load();
            video.play();
        }

    }

    function image(index) {
        $("#imageFile").css('display', 'block');
        $("#video_player").css('display', 'none');
        images.setAttribute("src", '{{ asset('/uploads/files/videos') }}/' + video_list[index]);
        setTimeout(() => {
            run();
        }, 2000);
    }
</script>
