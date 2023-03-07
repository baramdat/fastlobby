@extends('layouts.master')



@section('title')
    Edit Screen
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

            <h1 class="page-title">Edit Screen</h1>

            <div>

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>

                    <li class="breadcrumb-item"><a href="{{ url('/screen/list') }}">Screen list</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Edit Screen </li>

                </ol>

            </div>

        </div>


        <!-- PAGE-HEADER END -->



        <!-- ROW -->

        <div class="row">



            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body">

                        <form action="" id="add_user">

                            @csrf

                            <div class="row">

                                <div class="col-lg-6">

                                    <div class="form-group">

                                        <label for="exampleInputname" class="form-label mb-0">Name: <span
                                                class="text-danger">*</span> </label>

                                        <input type="text" class="form-control" id="name" name="name" value="{{$screen->name}}" required
                                            placeholder="Enter name of screen">
                                            <input type="hidden"  id="id" name="id" value="{{$screen->id}}">
                                    </div>

                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 col-xs-12 col-md-6">
                                    <h3>Videos</h3>
                                    @foreach ($videos as $vd)
                                        <div class="form-group">
                                            {{-- <label
                                                class="control-label col-9 col-xs-9 col-sm-7 col-lg-6">{{ $vd->name }}</label> --}}
                                            <div class="col-3 col-xs-3 col-sm-5 checkbox form-check">
                                                <input class="form-check-input position-static" id="videos" name="videos[]" type="checkbox" {{in_array($vd->name,json_decode($screen->videos))? 'checked': ''}} 
                                                    value="{{ $vd->name }}" />
                                                    <label class="form-check-label" for="gridCheck">
                                                        {{ $vd->name }}
                                                      </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-12 col-xs-12 col-md-6">
                                    <h3>Qr Codes</h3>
                                    @foreach ($qrs as $qr)
                                        <div class="form-group">
                                            {{-- <label
                                                class="control-label col-9 col-xs-9 col-sm-7 col-lg-6">{{ $qr->name }}</label> --}}
                                            <div class="col-3 col-xs-3 col-sm-5 checkbox form-check">
                                                <input class="form-check-input position-static"  id="qrs" name="qrs[]" type="checkbox" {{in_array($qr->id,json_decode($screen->qrs_codes))? 'checked': ''}}
                                                    value="{{ $qr->id }}" />
                                                    <label class="form-check-label" for="gridCheck">
                                                        {{ $qr->name }}
                                                      </label>
                                            </div>
                                        </div>
                                    @endforeach

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

            $("#add_user").on('submit', (function(e) {

                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({

                    url: '/api/update/screens',

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

                            toastr.error('Failed', Array.isArray(response.msg) ? response
                                .msg[0] : response.msg);

                        } else if (response["status"] == "success") {

                            toastr.success('Success', response["msg"])

                            $("#add_user")[0].reset();

                        }

                    },

                    error: function(error) {

                        // console.log(error);
                        toastr.error('Failed', response["msg"]);

                    }

                });

            }));
        });
    </script>
@endsection
