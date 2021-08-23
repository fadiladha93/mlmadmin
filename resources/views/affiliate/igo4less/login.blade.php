@extends('affiliate.layouts.app')

@section('content')
<?php
$showInfo = false;
$msg = null;
$type = null;
$token = null; // reset password token
if ($errors->getMessages()) {
    $showInfo = true;
    $error = $errors->getMessages()['error'][0];
    if (isset($errors->getMessages()['msg'])) {
        $msg = $errors->getMessages()['msg'][0];
    }
    if (isset($errors->getMessages()['type'])) {
        $type = $errors->getMessages()['type'][0];
    }
    if (isset($errors->getMessages()['token'])) {
        $token = $errors->getMessages()['token'][0];
    }
}
?>
<div class="container-fluid">
    @if($type == "reset-password")
    @include('affiliate.user.dlg_resetting_password')
    @endif
    <div class="row">
        <div class="col-md-4">
            <div class="aff_dashboard">
                <div class="logo">
                    <img src="{{asset('/assets/images/igo-logo.png')}}" width="100px">
                </div>
                <div class="m-login__signin">
                    @if($showInfo && $msg != null)
                    <div class="alert @if($error == 1) alert-danger @else alert-success @endif">
                        {!!$msg!!}
                    </div>
                    @endif
                    <h5>Login and Start Saving Now !</h5>
                    <form id="frmLogin" class="m-form" style="margin-top:30px;">
                        <input type="hidden" id="login_url" value="login" />
                        <div class="form-group m-form__group">
                            <label>iBuumerang Username</label>
                            <input type="text" class="form-control m-input" name="username">
                        </div>
                        <div class="form-group m-form__group">
                            <label>iBuumerang Email</label>
                            <input class="form-control m-input m-login__form-input--last" type="text" name="email">
                        </div>
                        <div class="m-login__form-action">
                            <button type="button" id="btnIgo4Less" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air btn-info">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8 d-none d-md-block bgimg" style="background-image: url(<?php echo asset('/assets/images/dist_login.jpg'); ?>);">
            <div class="welcome">
                <div class="title">
                    Your exclusive travel<br/>
                    and lifestyle benefits<br/>
                    are waiting.
                </div>
                <div class="msg"></div>
            </div>
        </div>
    </div>
</div>
@endsection