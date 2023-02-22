<style>
    .fullscreen-video {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: -1;
}
</style>

{{-- <div class="container">
    <div class="embed-responsive embed-responsive-16by9"> --}}
        <video class="fullscreen-video" id="video_player" width="100%" height="100%" autoplay onended="run();" auto
            muted>

            <source id="myvideo" src="{{ asset('/uploads/files/videos') . '/' . $video->name }}" type="video/mp4">

        </video>
    {{-- </div>
</div> --}}

<script type="text/javascript">
    var video_list = <?php echo json_encode($array); ?>;
    var video_index = 0;
    var video_player = document.getElementById("myvideo");
    var video = document.getElementById("video_player");
    //    run();
    function run() {

        if (video_index < video_list.length - 1) {
            video_index++;
        } else {
            video_index = 0;
        }
        video_player.setAttribute("src", '{{ asset('/uploads/files/videos') }}/' + video_list[video_index]);
        video.load();
        video.play();
    }

    
</script>
