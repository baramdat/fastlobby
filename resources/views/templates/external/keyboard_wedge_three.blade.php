@extends('layouts.min')

@section('title')
Barcode Scanner
@endsection

@section('content')
<!-- CONTAINER -->
<div class="container">
    <div class="page-header mb-2">
        <div>
            <h1 class="page-title">Keyboard wedge Scan</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Keyboard wedge</li>
                <li class="breadcrumb-item active" aria-current="page">Scan</li>
            </ol>
        </div>
    </div>

    <script type="text/javascript" src="{{asset('assets/js/jquery.scannerdetection.js')}}"></script>
    <script type="text/javascript">
        $(document).scannerDetection({
            timeBeforeScanTest: 200, // wait for the next character for upto 200ms
            startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
            endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
            avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
            onComplete: function(barcode, qty) {
           alert('OPL6845R scanner is detected!')
            }
        });
    </script>
</div>
@endsection