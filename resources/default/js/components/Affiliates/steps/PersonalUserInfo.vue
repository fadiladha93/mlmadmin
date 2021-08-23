<template>
    <div>
        <modal>
            <p class="lead">Please confirm your personal information. All fields are required.</p>
            <div class="row mt-5">
                <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">First Name</label>
                        <input v-model="userInfo.firstName" readonly type="text" class="form-control form-control-sm">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Last Name</label>
                        <input v-model="userInfo.lastName" readonly type="text" class="form-control form-control-sm">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Username</label>
                        <input v-model="userInfo.username" readonly type="text" class="form-control form-control-sm">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Recognition Name <span>*</span></label>
                        <input v-model="userInfo.recognitionName" type="text" class="form-control form-control-sm" v-on:keyup="checkForm">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group" v-if="userInfo.businessName">
                        <label class="control-label">Business Name</label>
                        <input v-model="userInfo.businessName" readonly type="text" class="form-control form-control-sm">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Email Address</label>
                        <input v-model="userInfo.emailAddress" readonly type="text" class="form-control form-control-sm">
                    </div>

                    <div class="form-group">
                        <label class="control-label">Phone <span>*</span></label>
                        <input v-model="userInfo.phone" type="text" class="form-control form-control-sm" v-on:keyup="checkForm">
                    </div>
                </div>

                <div class="col-12 text-center">
                    <p class="font-weight-bold mb-3">Is all of your information correct? If not, please contact customer service at support@ibuumerang.com</p>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" id="correctYes" v-model="userInfo.informationIsCorrect" :value="true" name="correctInformation">
                        <label class="custom-control-label" for="correctYes">Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" id="correctNo" v-model="userInfo.informationIsCorrect" :value="false" name="correctInformation">
                        <label class="custom-control-label" for="correctNo">No</label>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    import Modal from './../Modal';

    export default {
        name: "PersonalUserInfo",
        components: {
            Modal
        },
        data() {
            return {
                canContinue: false
            }
        },
        props: {
            userInfo: {
                required: true,
                type: Object
            }
        },
        methods: {
            checkForm() {
                if (this.userInfo.recognitionName && this.userInfo.phone) {
                    //
                }
            }
        },
        mounted() {
            //
        }
    }
</script>
