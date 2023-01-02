@extends('layouts.master')

@section('title')
Edit locker
@endsection

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <h1 class="page-title">Edit locker</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item"><a href="{{url('/locker/list')}}">Lockers</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Edit locker</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit locker</h3>
            </div>
            <div class="card-body">
                <form id="update_form">
                    @csrf
                    <input type="hidden" name="id" id="{{$locker->id}}" value="{{$locker->id}}">
                    <div class="row">
                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Locker No: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="number" name="number" value="{{$locker->number}}" placeholder="LS-001" required>
                        </div>

                        <div class="form-group col-lg-6 col-sm-12">
                            <?php
                            $sizesArr = App\Models\LockerSize::all();                            
                            ?>
                            <label class="form-label mb-0">Size: <span class="text-danger">*</span></label>
                            <select class="form-select" name="size_id" id="size_id" required>
                                <option value="">choose size</option>
                                @foreach ($sizesArr as $size)
                                    <option value="{{$size->id}}" <?=$size->id == $locker->size_id ? 'selected' : ''?>>{{$size->size}}</option>
                                @endforeach                                
                            </select>
                        </div>

                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Row: <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="row" name="row" placeholder="" value="{{$locker->row}}" required>
                        </div>

                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Column: <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="column" name="column" placeholder="" value="{{$locker->column}}" required>
                        </div>

                        <div class="form-group col-lg-6 col-sm-12">
                            <?php
                            $sitesArr = App\Models\Site::all();  
                            if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Staff')){
                                $sitesArr = App\Models\Site::where('id',Auth::user()->site->id)->get();
                            }                          
                            ?>
                            <label class="form-label mb-0">Site:</label>
                            <select class="form-select" name="site_id" id="site_id" required>
                                <option value="">choose site</option>
                                @foreach ($sitesArr as $site)
                                    <option value="{{$site->id}}" {{$site->id == $locker->site_id ? 'selected' : ''}}>{{ucwords($site->name)}}</option>
                                @endforeach                                
                            </select>
                        </div>

                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Relay #: <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="relay" name="relay" placeholder="" min="1" value="{{$locker->relay}}" required>
                        </div>

                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Status: <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="">choose status</option>
                                <option value="available" <?=$locker->status == 'available' ? 'selected' : ''?>>Available</option>
                                <option value="inactive" <?=$locker->status == 'inactive' ? 'selected' : ''?>>Inactive</option>                               
                            </select>
                        </div>
                        
                        <div class="form-group col-lg-12 col-sm-12">
                            <label for="" class="form-label mb-0">Comment :</label>
                            <textarea name="comment" id="comment" cols="30" rows="3" class="form-control">{{$locker->comment}}</textarea>
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
    $(document).ready(function () {

        $("#btnSubmit").on('click', (function (e) {
            $("#update_form").submit()
        }));

        $("#update_form").on('submit', (function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '/api/locker/update',
                type: "POST",
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spinner").css('display', 'inline-block');
                },
                complete: function () {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spinner").css('display', 'none');
                }, 
                success: function (response) {
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });


        }));
    });

</script>
@endsection
