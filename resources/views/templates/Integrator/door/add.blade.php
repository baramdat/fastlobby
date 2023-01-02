@extends('layouts.master')

@section('title')
Add locker
@endsection

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <h1 class="page-title">Add Door</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item"><a href="{{url('/integrator/door/list')}}">Doors</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Add Door</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add Door</h3>
            </div>
            <div class="card-body">
                <form id="add_form">
                    @csrf
                    <div class="row">

                        <div class="form-group col-lg-6 col-sm-12">
                            <?php
                            $user = Auth::user();
                            $sitesArr = App\Models\Site::where('user_id', $user->id)->get();
                            ?>
                            <label class="form-label mb-0">Site:</label>
                            <select class="form-select" name="site_id" id="site_id" required>
                                <option value="">choose site</option>
                                @foreach ($sitesArr as $site)
                                    <option value="{{$site->id}}">{{ucwords($site->name)}}</option>
                                @endforeach                                
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Site Relay Url: <span class="text-danger">*</span></label>
                            <select class="form-select" name="site_relay_url" id="site_relay_url" required>
                                
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Relay #: <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="relay_no" name="relay_no" placeholder="" min="1" required>
                        </div>
                        <div class="form-group col-lg-12 col-sm-12 text-center">
                            <button type="submit" class="btn btn-dealer w-25 btnSubmit" id="btnSubmit"> 
                                <i class="fa fa-spinner fa-pulse" style="display: none;"></i>
                                Save
                            </button>
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
<script>
    $(document).ready(function (e) {
        // add forms
        $("#add_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/integrator/door/add',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false, 
                cache: false,
                beforeSend: function () {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-pulse").css('display', 'inline-block');
                },
                complete: function () {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-pulse").css('display', 'none');
                },
                success: function (response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        $("#add_form")[0].reset();
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });
        }));
        
        //site url change dynamically
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

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection
