@extends('layouts.master')



@section('title')
    Add Site QR
@endsection



@section('content')
    <style>
        .hide {

            display: none;

        }
    </style>

    <div class="main-container container-fluid">



        <!-- PAGE-HEADER Breadcrumbs-->

        <div style="margin-top:20px">

            Hello {{ Auth::user()->first_name }} | <small
                class="badge bg-success">{{ ucwords(Auth::user()->roles->pluck('name')[0]) }}</small>

        </div>


        <div class="page-header">

            <h1 class="page-title">Add Site QR</h1>

            <div>

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>

                    <li class="breadcrumb-item"><a href="{{ url('/view/site/qr/list') }}">Site QR list</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Add Site QR </li>

                </ol>

            </div>

        </div>


        <div class="col-lg-10">

            <div class="card">

                <div class="card-body">

                    <form action="" id="add_site_qr">

                        @csrf

                        <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12" id="qrType">

                                <label class="form-label mb-0">Default QR Type: <span class="text-danger">*</span></label>
    
                                <select class="form-control" name="qr_type" id="qr_type" required>
    
                                    <option value="" selected>Choose QR Type</option>
    
                                    @foreach($qr_type as $qr)
    
                                        <option value="{{$qr->id}}">{{ucwords($qr->name)}} </option>
    
                                    @endforeach
                                    <option value="other" >Others</option>
                                </select>
    
                            </div>
                            <div class="form-group form-group col-lg-6 col-md-6 col-sm-12"  >

                                <label for="exampleInputname" class="form-label mb-0">QR Text: <span
                                        class="text-danger">*</span> </label>

                                <input type="text" class="form-control" id="qr_text" name="qr_text"
                                    placeholder="Enter text for qr">

                            </div>
                            <div class="form-group form-group col-lg-6 col-md-6 col-sm-12" id="otherType" style="display: none">

                                <label for="exampleInputname" class="form-label mb-0">Name: <span
                                        class="text-danger">*</span> </label>

                                <input type="text" class="form-control" id="type_name" name="type_name"
                                    placeholder="Enter name of type">

                            </div>
                            {{-- <h5>Type</h5>
                                <div class="form-group form-group col-lg-3 col-md-3 col-sm-12  checkbox form-check">
                                    <div class="card" >
                                        <div class="card-body">
                                            <input class="form-check-input position-static" id="default" name="default"
                                                type="checkbox" value="default" checked />
                                            <label class="form-check-label" for="gridCheck">
                                               Defualt
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-group col-lg-3 col-md-3 col-sm-12  checkbox form-check">
                                    <div class="card" >
                                        <div class="card-body">
                                            <input class="form-check-input position-static" id="other" name="other"
                                                type="checkbox" value="other" />
                                            <label class="form-check-label" for="gridCheck">
                                               Other
                                            </label>
                                        </div>
                                    </div>
                                </div> --}}
                        </div>
                </div>

                <div class="card-footer text-end">

                    <button type="submit" class="btn btn-primary btnSubmit" id="btnSubmit"> <i
                            class="fa fa-spinner fa-pulse" style="display: none;"></i>

                        Save</button>

                    <!-- <input type="button" class="btn btn-danger my-1" value="Cancel"> -->

                </div>

                </form>

            </div>



        </div>

    </div>

    <!-- ROW END -->

    </div>
@endsection



@section('bottom-script')
    <script type='text/javascript'
        src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>


    <script>
        $(document).ready(function(e) {



            // add user

            $("#add_site_qr").on('submit', (function(e) {

                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({

                    url: '/api/add/site/qr/code',

                    type: "POST",

                    data: formData,

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

                            toastr.error('Failed', response["msg"]);

                        } else if (response["status"] == "success") {

                            toastr.success('Success', response["msg"])

                            $("#add_site_qr")[0].reset();

                        }

                    },

                    error: function(error) {

                        // console.log(error);
                        toastr.error('Failed', response["msg"]);

                    }

                });

            }));
            $("#qr_type").on('change',function(e){
                if($(this).val()=='other'){
                    $('#otherType').css('display','inline-block');
                    $('#type_name').prop( "required", true );
                }else{
                    $('#otherType').css('display','none');
                    $('#type_name').prop( "required", false );
                }
            });
          $("#other").on('change',function(e){
            alert($(this).val());
            $('#default').prop( "checked", false );
            $('#otherType').css('display','inline-block');
            $('#qrType').css('display','none');
            $('#qr_type').prop( "required", false );
            $('#type_name').prop( "required", true );
          });
          $("#default").on('change',function(e){
            $('#other').prop( "checked", false );
            $('#otherType').css('display','none');
            $('#qrType').css('display','inline-block');
            $('#qr_type').prop( "required", true );
            $('#type_name').prop( "required", false );
          });



        });
    </script>
@endsection
