@extends('layouts.master')

@section('title')
Contact
@endsection

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">


    <!-- PAGE-HEADER Breadcrumbs-->
    <div class="page-header">
        <h1 class="page-title">Contact us</h1>
        <div>
            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a> </li>
                <li class="breadcrumb-item active" aria-current="page">Contact</li>

            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <div class="row">
        <!-- Button trigger modal -->
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#IncomingVideoCall">
            Launch demo modal
        </button>

        <!-- Modal -->
        <div class="modal fade" id="IncomingVideoCall" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Incoming video call</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Somenone is inviting you for a video call..
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Join</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Decline</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- CONTAINER END -->
@endsection

@section('bottom-script')

@endsection