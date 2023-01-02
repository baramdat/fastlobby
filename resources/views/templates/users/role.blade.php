@extends('layouts.master')

@section('title')
User role
@endsection

@section('content')
<style>
    .table td {
        padding: 5px !important;
    }

</style>
<div class="main-container container-fluid">

    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <div>
        <h1 class="page-title">User Role</h1>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{url('/user/list')}}">User list</a></li>
                <li class="breadcrumb-item active" aria-current="page">User role</li>
            </ol>
        </div>
        <button type="button" class="btn btn-dealer" data-bs-effect="effect-slide-in-right"
                data-bs-toggle="modal" href="#addRole"> <span class="fe fe-plus fs-14"></span> Add role</button>

    </div>
    <!-- PAGE-HEADER END -->




    <!-- filters -->
    <!-- <div class="card">
        <div class="card-body px-3 py-2 pt-3">
            <div class="form-row align-items-center">
                <div class="col-6 col-lg-5 mb-1">
                    <label for="" class="fw-bold mb-1">Search by name:</label>
                    <input type="text" id="user-list-latest-activity" class="form-control"
                        placeholder="search by name....">
                </div>
                <div class="col-6 col-lg-3 mb-1">
                    <label for="" class="fw-bold mb-1">Search by status:</label>
                    <select class="form-control form-select">
                        <option selected disabled>choose status</option>
                        <option value="1">Active</option>
                        <option value="2">Pending</option>
                        <option value="12">Warning</option>
                    </select>
                </div>
                <div class="col-12 col-lg-2 mb-1 ">
                    <label for="" class="mb-1"></label>

                    <button type="button" class="btn btn-danger btn-block">Reset</button>
                </div>
                <div class="col-12 col-lg-2 mb-1 ">
                    <label for="" class="mb-1"></label>

                    <button type="button" class="btn btn-secondary btn-block">Show</button>
                </div>
            </div>
        </div>
    </div> -->


    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card">
                <div class="card-body pt-4">
                    <table class="table table-bordered text-nowrap mb-0" id="responsive-datatable">
                        <thead class="border-top bg-light">
                            <tr>
                                <th class="bg-transparent border-bottom-0 fw-bold" style="width: 10%;">
                                    Tracking ID</th>
                                <th class="bg-transparent border-bottom-0 fw-bold">
                                    Role name</th>
                                <th class="bg-transparent border-bottom-0 fw-bold" style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $roles = \Spatie\Permission\Models\Role::all(); 
                             ?>
                            @foreach($roles as $role)
                            <tr class="border-bottom">
                                <td>{{ $role->id }}</td>
                                <td>
                                    <h6 class="mb-0 m-0 fs-14 fw-semibold">
                                        {{ $role->name }}</h6>
                                </td>
                                <td> 
                                    <div class="g-2">
                                        
                                        <a class="btn text-primary btn-sm btnEdit" data-bs-toggle="tooltip"
                                            data-bs-original-title="Edit" id='{{ $role->id }}'><span
                                                class="fe fe-edit fs-14" data-bs-effect="effect-slide-in-right"
                                                data-bs-toggle="modal" href="#editRole"></span></a>
                                        <a class="btn text-danger btn-sm btnDelete" data-bs-toggle="tooltip"
                                            data-bs-original-title="Delete" id='{{ $role->id }}'><span
                                                class="fe fe-trash-2 fs-14"></span></a>


                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End row -->
    <!-- MODALs -->
    <div class="modal fade" id="addRole">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Create role</h6><button aria-label="Close" class="btn-close"
                        data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="" id="add_role">
                        @csrf
                        <div class="form-group">
                            <label class="form-label text-start fw-bold">Role name: <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="" name="name" required placeholder="Role name">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dealer" id="btnSubmit"> <i
                                    class="fa fa-spinner fa-pulse" style="display: none;"></i>
                                Save</button>
                            <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
 
            </div>
        </div>
    </div>
    <div class="modal fade" id="editRole">
        <div class="modal-dialog modal-dialog-centered text-center" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Edit role</h6><button aria-label="Close" class="btn-close"
                        data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="" id="update_role">
                        @csrf
                        <input type="hidden" id='role_id' name="role_id">

                        <div class="form-group">
                            <label class="form-label text-start fw-bold">Role name: <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="Role name">
                        </div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-dealer" id="btnSubmit"> <i
                                    class="fa fa-spinner fa-spin" style="display: none;"></i>
                                Update changing</button>
                            <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection


@section('bottom-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function (e) {
        // add role
        $("#add_role").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/role/add',
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
                    // console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        $("#add_role")[0].reset();
                        // Using .reload() method.
                        location.reload();
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });
        }));

        // edit role
        $(document).on('click', '.btnEdit', function (e) {
            var role = $(this).attr('id');
            $.ajax({
                url: '/api/role/edit',
                type: "GET",
                dataType: "JSON",
                data: {
                    role: role

                },
                success: function (response) {
                    // console.log(response);
                    if (response["status"] == "fail") {
                        // toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        // toastr.success('Success', response["msg"])
                        $("#name").val(response["data"]["name"])
                        $("#role_id").val(response["data"]["id"])
                    }
                },
                error: function (error) {
                    // console.log(error);
                },
                async: false
            });
        });

        // UpdateRole
        $("#update_role").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/role/update',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spin").css('display', 'inline-block');
                },
                complete: function () {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spin").css('display', 'none');
                },
                success: function (response) {
                    // console.log(response);
                    if (response["status"] === "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] === "success") {
                        toastr.success('Success', response["msg"])
                        location.reload();
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });
        }));

        // delete
        $(document).on('click', '.btnDelete', function (e) {
            var role = $(this).attr('id');
            Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Role!",
                    type: "warning",
                    buttons: true,
                    confirmButtonColor: "#ff5e5e",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false,
                    dangerMode: true,
                    showCancelButton: true
                })
                .then((deleteThis) => {
                    if (deleteThis.isConfirmed) {
                        $.ajax({
                            url: '/api/role/delete',
                            type: "GET",
                            dataType: "JSON",
                            data: {
                                // role come from controller request
                                role: role

                            },
                            success: function (response) {

                                if (response["status"] == "fail") {
                                    toastr.error('Failed', response["msg"])
                                } else if (response["status"] == "success") {
                                    toastr.success('Success', response["msg"])
                                    // Using .reload() method.
                                    location.reload();
                                }
                            },
                            error: function (error) {
                                // console.log(error);
                            },
                            async: false
                        });
                    } else {
                        Swal.close();
                    }
                });
        });

    })

</script>
@endsection
