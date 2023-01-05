@extends('layouts.min')

@section('title')
Scan Qrcode
@endsection
<?php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
?>
@section('content')
<!-- CONTAINER -->
<div class="container">
    <div class="page-header mb-2">
        <div>
            <h1 class="page-title">Scan QR</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Scan</li>
                <li class="breadcrumb-item active" aria-current="page">QR</li>
            </ol>
        </div>
    </div>

    <div class="row d-flex justify-space-between">
        <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
            <div class="row">
                <h5>Scan Qr for Appointment Scheduling</h5>
                <div id="qr-reader" style="width:500px"></div>
                <div id="qr-reader-results"></div>
            </div>
        </div>
        <div class="qrdiv col-lg-6 col-xl-6 col-md-6 col-sm-12" style="padding-left:90px;">
            <h4><strong>Qr Code of " {{$site->name}} "</strong></h4>
            <div class="" style="margin-right:10px">
                <?php
                $link = env('APP_URL') . '/external/new/appointment/' . $site->unique_code
                ?>
                <img src="data:image/png;base64, {{ base64_encode(QrCode::encoding('UTF-8')->format('png')->margin(1)->size(220)->generate($link)) }}">
                <!--<img src="{{asset($site->qr_code)}}">-->
            </div><br>
        </div>
    </div>
    <div class="mt-3">
        <h4><strong>Instructions</strong></h4>
        <ol>
            <li>Scan provided Qr code and you recieved the self registration form.</li>
            <li>Fill the given form and select the Client whom you want to visit.</li>
            <li>Submit your form and your appointment request is sent to the specific client.</li>
            <li>After approval you will receive new text
                message and email with a QR code to present to the camera for entry.</li>
        </ol>
    </div>
</div>
@endsection

@section('bottom-script')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function docReady(fn) {
        // see if DOM is already available
        if (document.readyState === "complete" ||
            document.readyState === "interactive") {
            // call on next available tick
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function() {
        var resultContainer = document.getElementById('qr-reader-results');
        var lastResult, countResults = 0;

        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                ++countResults;
                lastResult = decodedText;
                // Handle on success condition with the decoded message.
                // $.ajax({
                //     type: "get",
                //     url: "/api/get/external/appointment/form",
                //     data: {
                //         data: lastResult
                //     },
                //     dataType: "JSON",
                //     success: function(response) {
                //         if (response["status"] == "fail") {
                //             alert('something went wrong!');
                //         } else if (response["status"] == "success") {
                //             window.location.href = response["url"];
                //             // document.querySelector('#last-barcode').innerHTML = $response["url"];
                //         }
                //     }
                // });

                // window.location.href = decodedText;
                // console.log(`Scan result ${decodedText}`, decodedResult);
            $.ajax({
            type: "get",
            url: "/api/get/appointment/details/"+lastResult,
            data: {site_id:'{{$site->id}}',is_external: 1},
            dataType: "JSON",
            success: function(response) {
                if (response["status"] == "fail") {
                    toastr.error('Error',response.msg)
                } else if (response["status"] == "success") {
                    window.location.href = response["url"];
                }
            }
        });
            }
        }
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 10,
                qrbox: 250
            });
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
        var data = $data.replace("/", "-");
        // document.querySelector('#last-barcode').innerHTML = data;
        console.log($data,data)
         $.ajax({
            type: "get",
            url: "/api/get/appointment/details/" + data,
            data: {site_id:'{{$site->id}}',is_external: 1},
            dataType: "JSON",
            success: function(response) {
                console.log(response)
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