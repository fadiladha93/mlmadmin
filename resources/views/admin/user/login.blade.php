@extends('admin.layouts.app')

@section('content')
<div class="container-fluid bgimg" style="background-image: url(<?php echo asset('/assets/images/bg-ncrease.png'); ?>);">
    <div class="row divSignin">
        <div class="col-md-4 offset-md-4 divInner">
            <div class="m-login__wrapper">
                <div class="m-login__logo">
                    <img src="{{asset('/assets/images/logo.png')}}">
                </div>
                <br>
                <div class="m-login__signin">
                    <div class="div-api-msg"></div>
                    <form id="frmLogin" class="m-form">
                        <input type="hidden" id="login_url" value="admin-login" />
                        <div class="form-group m-form__group">
                            <label>Email address</label>
                            <input type="email" class="form-control m-input" name="email">
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
                                <a href="javascript:;" id="m_login_forget_password" data-toggle="modal" data-target="#forget-password">Forget Password ?</a>
                            </div>
                        </div>
                        <div class="m-login__form-action">
                            <button type="button" id="btnLogin" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air btn-info">Sign In</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="forget-password" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <h5 class="modal-title">Forgot your password ?</h5>
            </div>
            <div class="modal-body text-center">
                <div>
                    Enter your email address to<br/>receive password resetting
                </div>
                <div style="margin-top: 10px;">
                    <div class="row">
                        <div class="col-sm-9">
                            <input type="text" id="email" class="form-control" />
                        </div>
                        <div class="col-sm-3">
                            <button id="btnForgotPass" class="ladda-button btn btn-primary" data-style="expand-right">
                                <span class="ladda-label">Submit</span>
                                <span class="ladda-spinner"></span>
                                <div class="ladda-progress" style="width: 0px;"></div>  
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 


@endsection

@push('scripts')
    <!-- Ladda -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Bind normal buttons
            Ladda.bind( '.ladda-button',{ timeout: 8500 });

            $("#btnForgotPass").on('click', function() {

                let distid = $("#email").val();
                let _token = "{{ csrf_token() }}";
                let url    = '{{ url('/forgot-password') }}';
               
                $.post(url, {distid: distid, _token: _token}, function(data) {
                    $("#forget-password").modal('hide');
                    $(".div-api-msg").removeClass('invisible');
                    
                    if (data.error == 1) {
                        $(".div-api-msg").addClass("alert alert-danger");
                    } else {
                        $(".div-api-msg").addClass("alert alert-success");
                    }
                    
                    $("div.div-api-msg").text(data.msg);
                });
            
            });
        });
    </script>
@endpush