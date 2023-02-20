@extends('layouts.master')

@section('title')
Scan Qrcode
@endsection

@section('content')
<!-- CONTAINER -->
<div class="main-container container-fluid">

    <!-- PAGE-HEADER Breadcrumbs-->
     <div style="margin-top:20px">
        Hello {{Auth::user()->first_name}} | <small class="badge bg-success">{{ucwords(Auth::user()->roles->pluck('name')[0])}}</small>
    </div>
    <div class="row">
    <div class="page-header">
        <div>
            <h1 class="page-title">Scan QR</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Scan</a> </li>
                <li class="breadcrumb-item active" aria-current="page">QR</li>
            </ol>
        </div>
    </div>
    <div id="qr-reader" style="width:500px"></div>
    <div id="qr-reader-results"></div>
    </div>

@endsection

@section('bottom-script')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function docReady(fn) {
        // see if DOM is already available
       
        if (document.readyState === "complete"
            || document.readyState === "interactive") {
            // call on next available tick
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function () {
        var resultContainer = document.getElementById('qr-reader-results');
        console.log(resultContainer);
        var lastResult, countResults = 0;
        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                ++countResults;
                lastResult = decodedText;
                   $.ajax({
                    type: "get",
                    url: "/api/get/appointment/details/" + lastResult,
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response)
                    if (response["status"] == "fail") {
                            toastr.error('Failed', response["msg"]);
                        } else if (response["status"] == "success") {
                            window.location.href = response["url"];
                        }
                    }
                });
                // Handle on success condition with the decoded message.
                // window.location.href = decodedText;
                // console.log(`Scan result ${decodedText}`, decodedResult);
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>

<script>
    var barcode = '';
    var interval;
    document.addEventListener('keydown', function(evt) {
        
        if (interval)
            clearInterval(interval);
        if (evt.code == 'Enter') {
            if (barcode)
                handleBarcode(barcode);
            barcode = '';
            return;
        }

        if (evt.key != 'Shift')
            barcode += evt.key;
        interval = setInterval(() => barcode = '', 20);
    });

    function handleBarcode(scanned_barcode) {
        $data = scanned_barcode;
        //document.querySelector('#last-barcode').innerHTML = $data;
         $.ajax({
            type: "get",
            url: "/api/get/appointment/details/" + data,
            dataType: "JSON",
            success: function(response) {
                if (response["status"] == "fail") {
                    alert('something went wrong!');
                } else if (response["status"] == "success") {
                    window.location.href = response["url"];
                }
            }
        });

    }
</script>
@endsection