@extends('layouts.min')

@section('title')
Scan Qrcode
@endsection

@section('content')
<!-- CONTAINER -->
<div class="container-fluid">

    <div class="row  d-flex justify-space-between">
        <div class="col-12">
            <h5>Live Streaming</h5>
             <a href="javascript:;" style="width:200px;" class="btn btn-primary btn-streaming" streaming-link="rtsp://admin:troiano10!@68.195.234.210:3067/chID=4&streamType=main&linkType=tcp">Click to start streaming</a>
        </div>
        
        <div class="col-lg-12 mt-3">
            <h4><strong>Instructions</strong></h4>
            <ol>
                <li>There will be multiple / dynamic live streaming links</li>
                <li>We need a way, where user will select the camera & then streaming will start for that cameram </li>
            </ol>
        </div>
       
    </div>
    
</div>
@endsection

@section('bottom-script')
<script>
    $(document).ready(function(){
        $(document).on('click','.btn-streaming',function(e){
            alert('Streaming feature will be implemented here')
        })
    })
</script>
@endsection