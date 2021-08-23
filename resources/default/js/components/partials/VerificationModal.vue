<template>
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container verification-modal">
                <div class="modal-body">
                    <div class="message">
                        Please enter your verification code
                    </div>
                    <div class="input-wrap">
                        <input type="text" class="form-control" v-model="verificationCode"/>
                        <div class="error-messages" v-if="errorMessage" style="display: block" >{{ errorMessage }}</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-submit mr-3" @click="$emit('submit', verificationCode)">Process</button>
                    <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VerificationModal',
        props: {
            errorMessage: String
        },
        data() {
            return {
                verificationCode: null,
            }
        },
    }
</script>
