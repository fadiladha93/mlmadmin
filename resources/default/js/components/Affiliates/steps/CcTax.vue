<template>
    <div>
        <modal>
            <p class="lead">Please confirm your personal information. All fields are required.</p>
            <div class="row mt-5">
                <div class="col-6">
                    <div v-if="ccData.creditCards.length > 0 && !formAddCreditCard" class="mb-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="row text-uppercase font-weight-bold">
                                    <div class="col-7">Payment Methods</div>
                                    <div class="col-5 text-right">Primary Card</div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div style="background: #eeeeee; border-radius: 3px; color: #000" class="p-4">
                                    <div v-if="ccData.creditCards.length > 0">
                                        <div class="row" v-for="cc in ccData.creditCards">
                                            <div class="col-9">{{ cc.token }}</div>
                                            <div class="col-3 text-center">
                                                <input v-model="idCCPrimary" type="radio" :value="cc.id" @change.prevent="updatePrimaryCC()">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" v-else>
                                        <div class="col-9">Ewallet</div>
                                        <div class="col-3 text-center">
                                            <input v-model="idCCPrimary" type="radio" checked="checked">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p v-if="ccData.creditCards.length === 0" class="font-weight-bold text-justify">
                        To keep your account active please add a card now. If you do not have a card please select e-wallet as your primary payment method and be sure you leave enough funds to cover the entire monthly subscription.
                    </p>

                    <div class="row" v-if="formAddCreditCard">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" v-model="creditCard.firstName" placeholder="Fist Name" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" v-model="creditCard.lastName" placeholder="Last Name" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" v-model="creditCard.token" placeholder="Token" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" v-model="creditCard.expMonth" placeholder="Expiration Month" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" v-model="creditCard.expYear" placeholder="Expiration Year" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <input type="text" v-model="creditCard.cvv" placeholder="CVV" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn-custom btn-blue" @click="saveCreditCard">Save</button>
                            <a href="#" @click="toggleFormCreditCard" class="text-uppercase custom-anchor">Cancel</a>
                        </div>
                    </div>

                    <a v-show="false"
                        href="#"
                        class="text-uppercase font-weight-bold custom-anchor"
                        style="color: #fff"
                        @click="toggleFormCreditCard"
                        v-if="ccData.creditCards.length > 0 && !formAddCreditCard"
                    >Add New Card</a>
                </div>

                <div class="col-6">
                    <div v-if="!ccData.isUSCitizen" v-show="!showSSNForm">
                        <p class="font-weight-bold text-justify">Please confirm that you live outside the United States by filling out the attached form. This form has no tax implications for you. It just helps the company by confirming that you live outside of the US. Thank you for taking the time to do this.</p>
                        <button class="btn-custom btn-blue text-uppercase" id="btnRequestForm" @click.prevent="openEsign" :disabled="ccData.formIsSigned">Request Form</button>
                    </div>

                    <div class="alert alert-danger text-uppercase" v-show="showErrorMessage" id="errorMessage"></div>

                    <div>
                        <a href="#" class="text-uppercase custom-anchor" @click="toggleSSN" v-show="!showSSNForm">I am a US citizen</a>
                        <a href="#" class="text-uppercase custom-anchor" @click="toggleSSN" v-show="showSSNForm">I am not a US citizen</a>
                        <div class="form-group mt-3" v-if="showSSNForm">
                            <label for="ssn" class="control-label">Please enter your SSN/EIN</label>
                            <input type="text" id="ssn" v-mask="'XXX-XX-XXXX'" class="form-control form-control-sm" v-model="ccData.ssn" placeholder="SSN">
                        </div>
                    </div>
                </div>
            </div>

            <template v-slot:buttons>
                <button class="btn-custom btn-yellow" @click="canContinue()">Continue</button>
            </template>
        </modal>
    </div>
</template>

<script>
    import Modal from './../Modal';
    import { mask } from 'vue-the-mask'

    export default {
        name: 'CcTax',
        components: {
            Modal
        },
        directives: { mask },
        data() {
            return {
                showSSNForm: false,
                idCCPrimary: null,
                creditCard: {},
                formAddCreditCard: false,
                showErrorMessage: false,
            }
        },
        props: {
            ccData: {
                type: Object,
                required: true
            }
        },
        methods: {
            toggleSSN() {
                this.showSSNForm = !this.showSSNForm;
            },
            updatePrimaryCC() {
                this.ccData.creditCards.filter(cc => {
                    cc.primary = (cc.id === this.idCCPrimary);
                });
            },
            saveCreditCard() {
                this.ccData.creditCards.push(this.creditCard);
                this.creditCard = {};
                this.toggleFormCreditCard();
            },
            toggleFormCreditCard() {
                this.formAddCreditCard = !this.formAddCreditCard;
            },
            openEsign() {
                axios({
                    url: '/tax/get-fw8ben',
                    method: 'GET',
                }).then(res => {
                    window.open(res.data.url, '_blank');
                }).catch(err => {
                    this.showErrorMessage = true;
                    document.getElementById('errorMessage').innerText = err.response.data.msg;
                });
            },
            canContinue() {
                if (this.ccData.isUSCitizen && !this.ccData.ssn) {
                    this.showErrorMessage = true;
                    document.getElementById('errorMessage').innerText = 'SSN is required.';
                } else if (this.ccData.isUSCitizen && this.ccData.ssn) {
                    this.$eventHub.$emit('next-step');
                } else if (!this.ccData.isUSCitizen) {
                    axios({
                        url: '/doc-is-signed',
                    }).then(res => {
                        if (res.data.isSigned) {
                            this.$eventHub.$emit('next-step');
                        }
                    }).catch(err => {
                        this.showErrorMessage = true;
                        document.getElementById('errorMessage').innerText = err.response.data.message;
                    });
                }
            }
        },
        mounted() {
            this.idCCPrimary = this.ccData.creditCards.filter(cc => cc.primary)[0].id;

            if (this.ccData.formIsSigned) {
                document.getElementById('btnRequestForm').innerText = 'Form already signed';
            }

            if (this.ccData.isUSCitizen) {
                this.toggleSSN();
            }
        }
    }
</script>
