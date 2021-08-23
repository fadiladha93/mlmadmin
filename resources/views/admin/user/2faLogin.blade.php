@extends('admin.layouts.app')

@section('content')
<div class="container-fluid bgimg" style="background-image: url(<?php echo asset('/assets/images/bg-ncrease.png'); ?>);">
    <div class="row divSignin">
        <div class="col-md-4 offset-md-4 divInner">
            <div class="m-login__wrapper">
                <div class="m-login__logo">
                    <img src="{{asset('/assets/images/logo.png')}}">
                </div>
                <div class="m-login__signin">
                    <form id="frm2FALogin" class="m-form">
                        <input type="hidden" name="email" value="{{$email}}" />
                        <div class="form-group m-form__group">
                            <label>Access Token</label>
                            <input type="text" class="form-control m-input" name="token">
                        </div>
                        <div class="text-center">
                            <a href="{{url('/admin')}}">Login Page</a>
                        </div>
                        <div class="m-login__form-action">
                            <button type="button" id="btn2FALogin" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air btn-info">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection