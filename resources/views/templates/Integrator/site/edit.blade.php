@extends('layouts.master')

@section('title')
Edit site
@endsection

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div style="margin-top:20px">
        Hello {{Auth::user()->first_name}} | <small class="badge bg-success">{{ucwords(Auth::user()->roles->pluck('name')[0])}}</small>
    </div>
    <div class="page-header">
        <h1 class="page-title">Edit Site</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item"><a href="{{url('/integrator/site/list')}}">Sites</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Edit Site</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Site</h3>
            </div>
            <div class="card-body">
                <form id="update_form">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{$site->id}}">
                    <div class="row">
                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Site Name: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Site name.." value="{{$site->name}}" required>
                        </div>

                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Site Address: <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" placeholder="Address.." required>{{$site->address}}</textarea>
                        </div>



                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Status: <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="">choose status</option>
                                <option value="active" <?= $site->status == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $site->status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <a href="javascript:;" class="mt-2 link-btn"><i class="fas fa-plus"></i> Add Site IP</a>
                            <table class="table table-borderless">
                                <tbody id="tbody">
                                    @if(isset($urls))
                                    @foreach($urls as $url)
                                    <tr>
                                    <td><input type="text" name="url[]" placeholder="128.0.1.." class="form-control" value={{$url}}></td>
                                    <td><a href="javascript:;" class="delete-row"><i class="fas fa-trash mt-2"></i></a></td>  
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
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
                url: '/api/integrator/site/update',
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

        $(document).on('click', '.link-btn', function() {

            var html = "";
            html += '<tr><td><input type="text" name="url[]" placeholder="128.0.1.." class="form-control"></td><td><a href="javascript:;" class="delete-row"><i class="fas fa-trash mt-2"></i></a></td></tr>';

            $("#tbody").append(html);

        })
        $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
@endsection