@extends('layouts.master')

@section('title')
Notifications
@endsection

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <h1 class="page-title">Notification</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Notification</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- filters -->
    <div class="card">
        <div class="card-body px-3 py-2 pt-3">
            <div class="form-row align-items-center">
                <div class="col-12 col-lg-6 mb-1">
                    <label for="" class="fw-bold mb-1">Search by registrant:</label>
                    <input type="text" id="search" class="form-control" placeholder="search by name....">
                </div>
                <div class="col-6 col-lg-2 mb-1">
                    <label for="" class="fw-bold mb-1">Search by state:</label>
                    <select class="form-control form-select" name="expiry_month" id="filterMonth">
                        <option value="All" selected>All</option>
                        <option value="">....</option>
                    </select>
                </div>
                <!-- <div class="col-6 col-lg-2 mb-1">
                    <label for="" class="fw-bold mb-1">Search by year:</label>
                    <select class="form-control form-select" name="expiry_year" id="filterYear">
                        <option value="All" selected>All</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                    </select>
                </div> -->
                <div class="col-12 col-lg-2 mb-1 ">
                    <label for="" class="mb-1"></label>

                    <button id="btnFilter" type="button" class="btn btn-dealer btn-block">Filter</button>
                </div>
                <div class="col-12 col-lg-2 mb-1 ">
                    <label for="" class="mb-1"></label>

                    <button id="btnReset" type="button" class="btn btn-outline-info btn-block">Reset</button>
                </div>
                
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card">
                <div class="card-header  mx-1">
                <div class="media">
                        <div class="media-body">
                            <h6 class="mb-0 mt-1 text-muted">Transactions notification</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <div class="grid-margin">
                        <div class="">
                            <div class="panel panel-primary">
                                <div class="panel-body tabs-menu-body border-0 pt-0">
                                    <div class="">
                                        <table id="data-table"  class="table table-bordered  mb-0">
                                            <thead class="border-top">
                                                <tr>
                                                    <th class="bg-transparent border-bottom-0" style="width: 5%;">
                                                        Refrence no</th>
                                                    <th class="bg-transparent border-bottom-0">
                                                        Registrant </th>
                                                    <th class="bg-transparent border-bottom-0">
                                                        State</th>
                                                    <th class="bg-transparent border-bottom-0">
                                                        Message</th>
                                                    <th class="bg-transparent border-bottom-0">
                                                        Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="border-bottom">
                                                    <td class="text-center">
                                                        <div class="mt-0 mt-sm-2 d-block">
                                                            <h6 class="mb-0 fs-14 ">
                                                                DRL-1234</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                    <div class="ms-3 mt-0 mt-sm-2 d-block">
                                                                <h6 class="mb-0 fs-14 ">
                                                                    Jhon Doe</h6>
                                                            </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="mt-0 mt-sm-3 d-block">
                                                                <h6 class="mb-0 fs-14 ">
                                                                    NY</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="mt-sm-2 d-block text-break ">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatum inventore nihil et quam incidunt voluptas repudiandae voluptatem, laboriosam distinctio sed fugiat dignissimos suscipit porro debitis beatae ut reiciendis omnis dolores!</span>
                                                    </td>
                                                    <td>
                                                        <div class="g-2">
                                                            <a href="{{url('/transaction/edit')}}" class="btn text-primary btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Edit"><span
                                                                    class="fe fe-edit fs-14"></span></a>
                                                            <a class="btn text-danger btn-sm btnDelete" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Delete"><span
                                                                    class="fe fe-trash-2 fs-14"></span></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <td class="text-center">
                                                        <div class="mt-0 mt-sm-2 d-block">
                                                            <h6 class="mb-0 fs-14 ">
                                                                DRL-1235</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                    <div class="ms-3 mt-0 mt-sm-2 d-block">
                                                                <h6 class="mb-0 fs-14 ">
                                                                    Donald</h6>
                                                            </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="mt-0 mt-sm-3 d-block">
                                                                <h6 class="mb-0 fs-14 ">
                                                                    FL</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="mt-sm-2 d-block">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatum inventore nihil et quam incidunt voluptas repudiandae voluptatem, laboriosam distinctio sed fugiat dignissimos suscipit porro debitis beatae ut reiciendis omnis dolores!</span>
                                                    </td>
                                                    <td>
                                                        <div class="g-2">
                                                            <a href="{{url('/transaction/edit')}}" class="btn text-primary btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Edit"><span
                                                                    class="fe fe-edit fs-14"></span></a>
                                                            <a class="btn text-danger btn-sm btnDelete" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Delete"><span
                                                                    class="fe fe-trash-2 fs-14"></span></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <td class="text-center">
                                                        <div class="mt-0 mt-sm-2 d-block">
                                                            <h6 class="mb-0 fs-14 ">
                                                                DRL-1236</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                    <div class="ms-3 mt-0 mt-sm-2 d-block">
                                                                <h6 class="mb-0 fs-14 ">
                                                                    Obama</h6>
                                                            </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="mt-0 mt-sm-3 d-block">
                                                                <h6 class="mb-0 fs-14 ">
                                                                    CA</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="mt-sm-2 d-block">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatum inventore nihil et quam incidunt voluptas repudiandae voluptatem, laboriosam distinctio sed fugiat dignissimos suscipit porro debitis beatae ut reiciendis omnis dolores!</span>
                                                    </td>
                                                    <td>
                                                        <div class="g-2">
                                                            <a href="{{url('/transaction/edit')}}"  class="btn text-primary btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Edit"><span
                                                                    class="fe fe-edit fs-14"></span></a>
                                                            <a class="btn text-danger btn-sm btnDelete" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Delete"><span
                                                                    class="fe fe-trash-2 fs-14"></span></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <td class="text-center">
                                                        <div class="mt-0 mt-sm-2 d-block">
                                                            <h6 class="mb-0 fs-14 ">
                                                                DRL-1237</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                    <div class="ms-3 mt-0 mt-sm-2 d-block">
                                                                <h6 class="mb-0 fs-14 ">
                                                                    Jhon Doe</h6>
                                                            </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="mt-0 mt-sm-3 d-block">
                                                                <h6 class="mb-0 fs-14 ">
                                                                    AL</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="mt-sm-2 d-block">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Voluptatum inventore nihil et quam incidunt voluptas repudiandae voluptatem, laboriosam distinctio sed fugiat dignissimos suscipit porro debitis beatae ut reiciendis omnis dolores!</span>
                                                    </td>
                                                    <td>
                                                        <div class="g-2">
                                                            <a href="{{url('/transaction/edit')}}"  class="btn text-primary btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Edit"><span
                                                                    class="fe fe-edit fs-14"></span></a>
                                                            <a class="btn text-danger btn-sm btnDelete" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Delete"><span
                                                                    class="fe fe-trash-2 fs-14"></span></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- pagination -->
    <div class="row" style="float:right !important;">
        <div class="col-md-12 col-xl-6 ">
            <ul class="pagination">
                <li class="page-item page-prev">
                    <a class="page-link" href="javascript:void(0)" tabindex="-1">Prev</a>
                </li>
                <li class="page-item active"><a class="page-link" href="javascript:void(0)">1</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0)">2</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0)">3</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0)">4</a></li>
                <li class="page-item page-next">
                    <a class="page-link" href="javascript:void(0)">Next</a>
                </li>
            </ul>
        </div>
    </div>

</div>
<!-- CONTAINER END -->
@endsection

@section('bottom-script')
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('click', '.btnDelete', function (e) {
            var id = $(this).attr('id')
            Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Tranasaction!",
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
                            url: 
                            type: "delete",
                            dataType: "JSON",
                            success: function (response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete User.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Usre card has been deleted.",
                                        "success");
                                        usercardCount()
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


</script>

@endsection
