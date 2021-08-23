<template>
    <div>
        <modal>
            <p class="lead">Thank You!</p>

            <div class="row mt-4 text-center">
                <div class="col-6 offset-3">
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="acceptPolicies" v-model="acceptPolicies">
                        <label class="ml-2 form-check-label" for="acceptPolicies">I agree to <a href="https://home.ibuumerang.com/ibuumerang-policies-and-procedures/" target="_blank">policies and procedures</a></label>
                    </div>
                </div>

                <div class="col-6 offset-3">
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="acceptTerms" v-model="acceptTerms">
                        <label class="ml-2 form-check-label" for="acceptTerms">I agree to <a href="https://home.ibuumerang.com/ibuumerang-ibuumerang-subscription-terms-and-conditions/" target="_blank">terms and conditions</a></label>
                    </div>
                </div>

                <div class="col-6 offset-3">
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="acceptPrivacy" v-model="acceptPrivacy">
                        <label class="ml-2 form-check-label" for="acceptPrivacy">I agree to <a href="https://home.ibuumerang.com/ibuumerang-privacy-policy/" target="_blank">privacy policy</a></label>
                    </div>
                </div>
            </div>

            <template v-slot:buttons>
                <button class="btn-custom btn-yellow" :disabled="canFinish()" @click="finish">Finish</button>
            </template>
        </modal>
    </div>
</template>

<script>
    import Modal from './../Modal';

    export default {
        name: 'Thanks',
        data() {
            return {
                acceptTerms: false,
                acceptPolicies: false,
                acceptPrivacy: false
            }
        },
        components: {
            Modal
        },
        methods: {
            finish() {
                this.$eventHub.$emit('finish');
            },
            canFinish() {
                return !this.acceptTerms || !this.acceptPolicies || !this.acceptPrivacy;
            }
        }
    }
</script>

<style scoped>
    p {
        font-size: 25px;
        color: #fff;
    }

    p.lead {
        font-size: 40px;
    }

    .form-group label {
        font-size: 15px;
    }
</style>
