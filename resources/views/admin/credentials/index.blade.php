@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <form method="post" class="m-form m-form__section--first m-form--label-align-right">
        @csrf
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Edit Credentials
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <button type="submit" class="btn btn-success btn-sm m-btn--air" >Save</button>&nbsp;
                    <a href="{{url('/settings/credentials')}}" class="btn btn-info btn-sm m-btn--air">Back</a>
                </div>
            </div>
            <div class="m-portlet__body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="email-tab" data-toggle="tab" href="#maildiv" role="tab" aria-controls="home"
                            aria-selected="true">Email</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="sms-tab" data-toggle="tab" href="#smsdiv" role="tab" aria-controls="profile"
                            aria-selected="false">SMS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="2fa-tab" data-toggle="tab" href="#twofadiv" role="tab" aria-controls="contact"
                            aria-selected="false">2FA</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" id="ziplingo-tab" data-toggle="tab" href="#ziplingodiv" role="tab" aria-controls="contact"
                            aria-selected="false">ZipLingo</a>
                        </li> --}}
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="maildiv">
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">Driver</label>
                                <div class="col-md-9">
                                    <select name="mail[driver]">
                                        <option value="sendgrid" @if($mail['driver']->value=='sendgrid') selected @endif>Sendgrid</option>
                                        <option value="mailgun" @if($mail['driver']->value=='mailgun') selected @endif>MailGun</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">Host</label>
                                <div class="col-md-9">
                                    <input type="text" name="mail[host]" value="{{ $mail['host']->value }}" /> 
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">User</label>
                                <div class="col-md-9">
                                    <input type="text" name="mail[username]" value="{{ $mail['username']->value }}" /> 
                                </div>
                            </div>

                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">Password</label>
                                <div class="col-md-9">
                                    <input type="text" name="mail[password]" value="{{ $mail['password']->value }}" /> 
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">From name</label>
                                <div class="col-md-9">
                                    <input type="text" name="mail[from_name]" value="{{ $mail['from_name']->value }}" /> 
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">From Address</label>
                                <div class="col-md-9">
                                    <input type="text" name="mail[from_address]" value="{{ $mail['from_address']->value }}" /> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="smsdiv">
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">Driver</label>
                                <div class="col-md-9">
                                    <select name="sms[driver]">
                                        <option value="twillo" @if($sms['driver']=='twillo') selected @endif>Twillo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">token</label>
                                <div class="col-md-9">
                                    <input type="text" name="sms[token]" value="{{ $sms['token']->value }}" /> 
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">From</label>
                                <div class="col-md-9">
                                    <input type="text" name="sms[from]" value="{{ $sms['from']->value }}" /> 
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">sid</label>
                                <div class="col-md-9">
                                    <input type="text" name="sms[sid]" value="{{ $sms['sid']->value }}" /> 
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="twofadiv">
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">Driver</label>
                                <div class="col-md-9">
                                    <select name="fa2[driver]">
                                        <option value="twillo" @if($fa2['driver']=='twillo') selected @endif>Twillo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-3 col-form-label">Key</label>
                                <div class="col-md-9">
                                    <input type="text" name="fa2[key]" value="{{ $fa2['key']->value }}" /> 
                                </div>
                            </div>
                        </div>
                    </div>    
            </div>
        </div>
    </form>
</div>
@endsection