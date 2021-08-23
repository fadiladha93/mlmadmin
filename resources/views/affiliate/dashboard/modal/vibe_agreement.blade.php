<div class="modal" id="vibeAgreementModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <img alt="" src="assets/images/logo.png">
                    <br/>
                    <p>Welcome to ibüümerang!</p>
                    <p>Please confirm the information is correct</p>
                </div>
            </div>
            <div class="modal-body">
                <h4>User Profile</h4>
                <div class="m-separator"></div>
                <form id="frmVibeImportUser" class="m-form m--align-left" action="#" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" name="firstname" value="{{$user->firstname}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Last Name</label>
                            <input class="form-control" name="lastname" value="{{$user->lastname}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Username</label>
                            <input class="form-control" name="username" value="{{$user->username}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="email">Email</label>
                            <input class="form-control" name="email" value="{{$user->email}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Display Name</label>
                            <input class="form-control" name="display_name" value="{{$user->display_name}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label">Business Name</label>
                            <input class="form-control" name="business_name" value="{{$user->business_name}}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="phonenumber">Phone</label>
                            <input class="form-control" name="phonenumber" value="{{$user->phonenumber}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-form-label" for="mobilenumber">Mobile</label>
                            <input class="form-control" name="mobilenumber" value="{{$user->mobilenumber}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <label class="m-checkbox m-checkbox--focus">
                            <input type="checkbox" name="agree_all"> I have read and agree to:
                            <div style="margin-top: 10px" class="d-flex flex-column">
                                <a href="/agreements/terms-and-conditions" target="_blank">Terms &amp; Conditions</a>
                                <a href="/agreements/policies-and-procedures" target="_blank">Policies &amp; Procedures</a>
                                <a href="/agreements/privacy-policy" target="_blank">Privacy Policy</a>
                                <a>Allow my sponsor to contact me</a>
                                <a>Allow Notifications (marketing)</a>
                            </div>
                            <span></span>
                        </label>
                    </form>
                </div>
                <div class="form-group pull-right">
                    <button type="submit" id="btnVibeAgree" class="btn btn-primary mb-2">Accept & Continue</button>
                </div>
            </div>
        </div>
    </div>
</div>
