@extends('layouts.master')

@section('title')
Edit locker
@endsection

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <h1 class="page-title">Edit Door</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item"><a href="{{url('/integrator/door/list')}}">Doors</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Edit door</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit door</h3>
            </div>
            <div class="card-body">
                <form id="update_form">
                    @csrf
                    <input type="hidden" name="id" id="{{$door->id}}" value="{{$door->id}}">
                    <div class="row">
                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Relay No: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="relay_no" name="relay_no" value="{{$door->relay_no}}" placeholder="001" required>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <?php
                            $sitesArr = App\Models\Site::where('user_id', Auth::user()->id)->get();
                            ?>
                            <label class="form-label mb-0">Site:</label>
                            <select class="form-select" name="site_id" id="site_id" required>
                                <option value="">choose site</option>
                                @foreach ($sitesArr as $site)
                                <option value="{{$site->id}}" {{$site->id == $door->site_id ? 'selected' : ''}}>{{ucwords($site->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Site Relay Url: <span class="text-danger">*</span></label>
                            <select class="form-select" name="site_relay_url" id="site_relay_url" required>
                                @if(isset($urls))
                                @foreach($urls as $url)
                                <option value="{{$url}}" {{$url == $door->site_relay_url ? 'selected' : ''}}>{{ucwords($url)}}</option>
                                @endforeach
                                @if(! in_array($door->site_relay_url,$urls))
                                <option value="" selected>Choose url</option>
                                @endif

                                @endif
                            </select>
                        </div>
                        <div class="form-group col-lg-12 col-sm-12 text-center">
                            <button type="submit" class="btn btn-dealer btnSubmit" id="btnSubmit">
                                <i class="fa fa-spinner fa-pulse" style="display: none;"></i>
                                Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- CONTAINER END -->
@endsection

@section('bottom-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    //DropZone
    Dropzone.autoDiscover = false;
    $(document).ready(function() {

        $("#btnSubmit").on('click', (function(e) {
            $("#update_form").submit()
        }));

        $("#update_form").on('submit', (function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '/api/integrator/door/update',
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spinner").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spinner").css('display', 'none');
                },
                success: function(response) {
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                    }
                },
                error: function(error) {
                    // console.log(error);
                }
            });


        }));

        $('#site_id').on('change', function() {
            $site = $(this).val();
            $.ajax({
                url: '/api/integrator/site/urls/show',
                type: "get",
                data: {
                    data: $site,
                },
                dataType: "JSON",
                cache: false,
                beforeSend: function() {},
                complete: function() {},
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "success") {
                        $('#site_relay_url').html('');
                        total = $('#site_relay_url').html(response["data"]);
                    } else if (response["status"] == "fail") {
                        $("#site_relay_url").html('');
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