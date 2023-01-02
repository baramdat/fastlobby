@extends('layouts.min')

@section('title')
404 not found
@endsection

@section('content')

<style>
    .app-content {
             margin-left: 2px !important;
        }
</style>
<!-- CONTAINER -->
<div class="container-fluid">
    <div class="row" style="margin-top:30vh">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">404 not found</h3>
                </div>
                <div class="card-body">
                    <div id="divSuccess" class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6 text-center">
                            <i class="fe fe-cloud-off text-danger" style="font-size:4rem"></i>
                            <br>
                            The resouce you are looking for is either removed or does not exists!
                            <br>
                        </div>
                        <div class="col-lg-3"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </div>
</div>
<!-- CONTAINER END -->
@endsection

@section('bottom-script')
<script>
    $(document).ready(function(e) {});
</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection