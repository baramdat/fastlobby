@extends('layouts.master')

@section('title')
    Location Details
@endsection

@section('content')
    <style>
        .link-btn {
            color: #282F53;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
    <!-- CONTAINER -->
    <div class="main-container container-fluid">


        <!-- PAGE-HEADER Breadcrumbs-->
        <div style="margin-top:20px">
            Hello {{ Auth::user()->first_name }} | <small
                class="badge bg-success">{{ ucwords(Auth::user()->roles->pluck('name')[0]) }}</small>
        </div>
        <div class="page-header">
            <h1 class="page-title">Location Details</h1>

        </div>
        <!-- PAGE-HEADER END -->
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">

                    <div class="col-lg-6">
                        <label for="exampleInputname" class="form-label mb-0">Text</label>
                        <input type="text" class="form-control mt-3 mb-2" name="description" id="description"
                            value="">
                    </div>
                    <div class="col-lg-6">
                        <label for="exampleInputname" class="form-label mb-0">Images</label>
                        <img id="image" src="" alt="" height="100%" width="100%">
                    </div>
                    <div class="col-lg-6">
                        <button class="btn btn-light mt-2" id="previous"
                            style="border: 1px solid #555555;border-radius: 6px;background: none;">Previous</button>
                        <button class="btn btn-light mt-2" id="hoger"
                            style="border: 1px solid #555555;border-radius: 6px;background: none;">Next</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- CONTAINER END -->
@endsection

@section('bottom-script')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function(e) {
            var images = <?php echo json_encode($array); ?>;
            var text = <?php echo json_encode($text); ?>;
            // var images = [
            //     "http://lorempixel.com/100/100/",
            //     "http://lorempixel.com/150/100/",
            //     "http://lorempixel.com/200/100/",
            // ];
            var imageIndex = 0;
            var iLength = images.length - 1;
            $("#previous").prop("disabled", true);
            if (iLength == 0) {
                $("#hoger").prop("disabled", true);
            }
            $("#previous").on("click", function() {
                imageIndex = (imageIndex - 1);
                $("#image").attr('src', '{{ asset('/uploads/files/wayfinder') }}/' + images[imageIndex]);
                $("#description").val(text[imageIndex]);

                $("#hoger").prop("disabled", false);
                if (imageIndex == 0) {
                    $(this).prop("disabled", true);
                }
            });

            $("#hoger").on("click", function() {
                $("#previous").prop("disabled", false);
                imageIndex = (imageIndex + 1);
                $("#image").attr('src', '{{ asset('/uploads/files/wayfinder') }}/' + images[imageIndex]);
                $("#description").val(text[imageIndex]);
                if (imageIndex == iLength) {
                    $(this).prop("disabled", true);
                }
            });
            $("#image").attr('src', '{{ asset('/uploads/files/wayfinder') }}/' + images[0]);
            $("#description").val(text[0]);
        });
    </script>
@endsection
