@extends('affiliate.layouts.app')

@section('content')
<div class="container-fluid bgimg" style="background-image: url(<?php echo asset('/assets/images/bg_login.jpeg'); ?>);">
    <div class="row divThankyou">
        <div class="modal fade" id="dd_thankyou" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body" id="dlgThankyou" >
                        <div style="padding:10px 0px;">
                            <img src="{{asset('/assets/images/header-logo.png')}}" width="160px;">
                        </div>
                        <div style="background-image: url(<?php echo asset('/assets/images/startup.jpeg'); ?>);background-size:cover;">
                            <div id="txt1"><strong>Congratulations!</strong> Your application for an internship with Xstream Travel has been submitted.<br/><br/>Please check your email <br/>for instructions to complete the process.</div>
                            <div id='btn'>
                                <a href="{{url('/')}}" class="btn btn-danger">Okay</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection