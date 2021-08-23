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
                    <img src="{{asset('/assets/images/logo.png')}}" width="130px;">
                    
                </div>
                <br>
                <div class="m-login__signin">
                    @if($showInfo && $msg != null)
                    <div class="alert @if($error == 1) alert-danger @else alert-success @endif">
                        {!!$msg!!}
                    </div>
                    @endif
                    <form id="frmLogin" class="m-form">
                        <input type="hidden" id="login_url" value="login" />
                        <div class="form-group m-form__group">
                            <label>Username / Distributor ID</label>
                            <input type="text" class="form-control m-input" name="username">
                        </div>
                        <div class="form-group m-form__group">
                            <label>Password</label>
                            <input class="form-control m-input m-login__form-input--last" type="Password" name="password">
                        </div>
                        <div class="row m-login__form-sub">
                            <div class="col m--align-left">
                                <label class="m-checkbox m-checkbox--focus">
                                    <input type="checkbox" name="remember"> Remember me
                                    <span></span>
                                </label>
                            </div>
                            <div class="col m--align-right">
                                <a href="#" class="showDlg_s" tag="{{url('/forgot-password')}}" id="m_login_forget_password">Forget Password ?</a>
                            </div>
                        </div>
                        <div class="m-login__form-action">
                            <button type="button" id="btnLogin" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air btn-info">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8 d-none d-md-block bgimg" style="background-image: url(<?php echo asset('/assets/images/bg-ncrease.png'); ?>);">
            <div class="welcome">
                <div class="title">Welcome to <img src="{{asset('/assets/images/logo.png')}}" /></div>
            </div>
        </div>
    </div>
</div>
@endsection