@extends('layouts.master')

@section('title')
Add Site
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
        Hello {{Auth::user()->first_name}} | <small class="badge bg-success">{{ucwords(Auth::user()->roles->pluck('name')[0])}}</small>
    </div>
    <div class="page-header">
        <h1 class="page-title">Add Site</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item"><a href="{{url('/site/list')}}">Sites List</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Add Site</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add Site</h3>
            </div>
            <div class="card-body">
                <form id="add_form">
                    @csrf
                    <div class="row">
                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Site Name: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Site name.." required>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Site Address: <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" placeholder="Address.." required></textarea>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <label class="form-label mb-0">Status: <span class="text-danger">*</span></label>
                            <select class="form-control form-select" name="status" required>
                                <option value="">Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">In Active</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <a href="javascript:;" class="mt-2 link-btn"><i class="fas fa-plus"></i> Add Site IP</a>
                            <table class="table table-borderless">
                                <tbody id="tbody">

                                </tbody>
                            </table>
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
    $(document).ready(function(e) {
        // add forms
        $("#add_form").on('submit', (function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{url('/api/site/add')}}",
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-pulse").css('display', 'inline-block');
                },
                complete: function() {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-pulse").css('display', 'none');
                },
                success: function(response) {
                    // console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        $("#add_form")[0].reset();
                        $('.delete-row').closest('tr').remove();
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

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection