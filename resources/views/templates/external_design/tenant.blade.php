@extends('layouts.min')

@section('title')
    Contact Tenant
@endsection

@section('content')
    <!-- CONTAINER -->

    <style>
        .hide {
            display: none;
        }

        .cursor-left {
            text-align: center;
        }
    </style>

    <div class="main-container container-fluid">


        <!-- PAGE-HEADER Breadcrumbs-->

        <div class="row">
            <div class="col-lg-3">
                <a class="btn btn-info mt-5" href="{{ URL::previous() }}" style="float: right;"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
            </div>
            <div class="col-lg-6">
               
                <div class="page-header">               
                    <h1 class="page-title">Contact Tenant</h1>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Contact Tenant</h3>
                    </div>
                    <div class="card-body">
                        <form id="add_form">
                            @csrf
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                    <label class="form-label mb-0">Your Name: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="John Doe.." required>
                                    <input type="hidden" class="form-control" id="site_id" name="site_id" value="">
                                </div>

                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                    <label class="form-label mb-0">Your Email: <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="john@fastlobby.com" required>
                                </div>
                                <div class="form-group col-lg-12 col-sm-12">
                                    <label class="form-label mb-0">Select Tenant: <span class="text-danger">*</span></label>
                                    <select class="form-control form-select" id="tenant" name="tenant" required>
                                        @if (isset($tenants) && sizeof($tenants) > 0)
                                            @foreach ($tenants as $tenant)
                                                <option value="{{ $tenant->id }}">{{ $tenant->first_name }}
                                                    {{ $tenant->last_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-lg-12 col-sm-12">
                                    <label class="form-label mb-0">Message: <span class="text-danger">*</span></label>
                                    <textarea type="text" class="form-control" rows="3" id="message" name="message" required></textarea>
                                </div>
                            </div>
                            <div class="form-group col-lg-12 col-sm-12">
                                <button type="submit" class="btn btn-dealer w-100 btnSubmit" id="btnSubmit">
                                    <i class="fa fa-spinner fa-pulse" style="display: none;"></i>
                                    Forward Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTAINER END -->
@endsection

@section('bottom-script')
    <script type='text/javascript'
        src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script>
        $(document).ready(function(e) {

            $("#add_form").on('submit', (function(e) {

                e.preventDefault();

                $.ajax({
                    url: '/api/external/contact/tenant',
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
                        if (response["status"] == "fail") {
                            toastr.error('Failed', response["msg"])
                        } else if (response["status"] == "success") {
                            toastr.success('Success', response["msg"])
                            $("#add_form")[0].reset();
                            //window.close();
                        }
                    },
                    error: function(error) {
                        // console.log(error);
                    }
                });
            }));

        });
    </script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
