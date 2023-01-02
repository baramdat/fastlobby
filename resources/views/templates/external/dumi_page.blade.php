@extends('layouts.min')

@section('title')
Add Appointment
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center g-2">
        <div class="col-lg-6">
            <div id="qr-shaded-region" style="position: absolute; border-width: 62px 124px; border-style: solid; border-color: rgba(0, 0, 0, 0.48); box-sizing: border-box; inset: 0px;">
                <div style="position: absolute; background-color: rgb(255, 255, 255); width: 40px; height: 5px; top: -5px; left: 0px;"></div>
                <div style="position: absolute; background-color: rgb(255, 255, 255); width: 40px; height: 5px; top: -5px; right: 0px;"></div>
                <div style="position: absolute; background-color: rgb(255, 255, 255); width: 40px; height: 5px; top: 255px; left: 0px;"></div>
                <div style="position: absolute; background-color: rgb(255, 255, 255); width: 40px; height: 5px; top: 255px; right: 0px;"></div>
                <div style="position: absolute; background-color: rgb(255, 255, 255); width: 5px; height: 45px; top: -5px; left: -5px;"></div>
                <div style="position: absolute; background-color: rgb(255, 255, 255); width: 5px; height: 45px; top: 215px; left: -5px;"></div>
                <div style="position: absolute; background-color: rgb(255, 255, 255); width: 5px; height: 45px; top: -5px; right: -5px;"></div>
                <div style="position: absolute; background-color: rgb(255, 255, 255); width: 5px; height: 45px; top: 215px; right: -5px;"></div>
            </div>
        </div>
        <div class="col-lg-6">Column</div>
    </div>
</div>
@endsection