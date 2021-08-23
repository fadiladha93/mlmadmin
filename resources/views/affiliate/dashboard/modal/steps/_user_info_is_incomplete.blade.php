<div class="modal">
    <div class="modal-dialog modal-lg" role="document" id="modalUserInfoIsIncomplete" data-step="{{ $step }}">
        <div class="modal-content">
            <div class="modal-body dlgQuestionList">
                <div style="padding:10px 0px;">
                    <img src="{{asset('/assets/images/logo.png')}}" width="180px;">
                </div>
                <div class="cm-body" style="background: #4aafd1">
                    <div class="cm-body-inner">
                        <h3><strong>Please confirm your personal information. All fields are required.</strong></h3>
                        <div class="card-wrap" id=frmUpdateUser>
                            <div class="card-lt">
                                <div class="card-field">
                                    <div class="card-field-full">
                                        <label>First Name <span class="req">*</span></label>
                                        <input type="text" name="firstname" class="input-box" readonly value="{{ $user->firstname }}">
                                    </div>
                                    <div class="card-field-full">
                                        <label>Last Name <span class="req">*</span></label>
                                        <input type="text" name="lastname" class="input-box" readonly value="{{ $user->lastname }}">
                                    </div>
                                    <div class="card-field-full">
                                        <label>Username <span class="req">*</span></label>
                                        <input type="text" name="username" class="input-box" readonly value="{{ $user->username }}">
                                    </div>
                                    <div class="card-field-full">
                                        <label>Recognition Name <span class="req">*</span></label>
                                        <input type="text" name="recognition_name" class="input-box" value="{{ $user->recognition_name }}">
                                    </div>
                                </div>
                            </div>
    
                            <div class="card-rt">
                                <div class="card-field">
                                    @if ($user->business_name)
                                        <div class="card-field-full">
                                            <label>Business Name <span class="req">*</span></label>
                                            <input type="text" name="business_name" class="input-box business-input" readonly value="{{ $user->business_name }}">
                                        </div>
                                    @endif
                                    <div class="card-field-full">
                                        <label>Email address <span class="req">*</span></label>
                                        <input type="text" name="email" class="input-box business-input" readonly value="{{ $user->email }}">
                                    </div>
                                    <div class="card-field-full">
                                        <label>Phone <span class="req">*</span></label>
                                        <input type="text" name="phonenumber" class="input-box business-input" value="{{ $user->phonenumber }}">
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-12">
                                <div class="card-field text-center text-weight-bold">
                                    <div class="card-field-full">
                                        <label>Is all of your information correct? If not, please contact customer service at support@ibuumerang.com</label><br>
                                        <input class="correct-radio" type="radio" name="is_correct" value="1"> YES
                                        <input class="correct-radio" type="radio" name="is_correct" value="0"> NO
                                    </div>
                                </div>
                            </div>
    
                            <div class="submit-cart submit-btn-grp">
                                <button id="btnNextStep" class="yellow-btn">CONTINUE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

@include('affiliate.layouts.new_modal_style')

@push('scripts')
<script>
    $(document).ready(function() {
        // $('#modalUserInfoIsIncomplete').modal('show');
    });
    
</script>
@endpush