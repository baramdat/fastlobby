@extends('layouts.min')

@section('title')
Scan Qrcode
@endsection

@section('content')
<style>
    @media (min-width: 992px) {
        .app-content {
            margin-left: 0px !important;
        }
    }

    @media (max-width: 991px) {
        .app-content {
            min-width: 100%;
        }
    }
</style>
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
    <div class="row">
        <div class="col-lg-3 col-xl-3 col-md-6 col-sm-12">
            <input type="hidden" id="siteId" value="{{$site->id}}">
            <input type="hidden" id="doorId" value="{{$door->id}}">
            <input type="hidden" id="relay" value="{{$door->relay_no}}">
        </div>
        <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12">
            <div class="row">
                <h5 id="header">Scan Qr for Door Access</h5>
                <div id="qr-reader" style="width:500px"></div>
                <div id="qr-reader-results"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom-script')
<script src="https://unpkg.com/html5-qrcode"></script>
<script type="text/javascript">
    var site = $('#siteId').val();
    var door = $('#doorId').val();

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

                // alert(decodedText + ' ' + site + ' ' + door);
                $.ajax({
                    type: "get",
                    url: "/api/door/access/update/" + lastResult,
                    dataType: "JSON",
                    data: {
                        site: site,
                        door: door
                    },
                    cache: false,
                    
                    success: function(response) {
                        console.log(response)
                       if (response["status"] == "fail") {
                            toastr.error('Failed', response["msg"])
                        } else if (response["status"] == "success") {
                            toastr.success('Success', response["msg"]);
                            DoorStateChange();
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
        
            function DoorStateChange() {
            console.log('function called');
            var doorId = $('#doorId').val();
            var relay = $('#relay').val();
            var state = "2";
            $.ajax({
                url: '/api/integrator/relay/state/update',
                type: "POST",
                dataType: "JSON",
                data: {
                    doorId: doorId,
                    relay: relay,
                    state: state
                },
                   beforeSend: function() {
                        console.log('calling')
                    },
                    complete: function() {
                        console.log('calling off')
                    },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                    }
                },
                error: function(error) {
                    // console.log(error);
                },
                // async: false
            });
        }
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
        document.querySelector('#last-barcode').innerHTML = $data;
        $.ajax({
            type: "get",
            url: "/api/door/access/update/" + lastResult,
            dataType: "JSON",
            data: {
                site: site,
                door: door
            },
            cache: false,
            beforeSend: function() {
                console.log('calling')
            },
            complete: function() {
                console.log('calling off')
            },
            success: function(response) {
                console.log(response)
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"]);
                    DoorStateChange();
                }
            }
        });

        function DoorStateChange() {
            console.log('function called');
            var doorId = $('#doorId').val();
            var relay = $('#relay').val();
            return relay;
            var state = "2";
            $.ajax({
                url: '/api/integrator/relay/state/update',
                type: "POST",
                dataType: "JSON",
                data: {
                    doorId: doorId,
                    relay: relay,
                    state: state
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                    }
                },
                error: function(error) {
                    // console.log(error);
                },
                // async: false
            });
        }
    }
</script>
@endsection