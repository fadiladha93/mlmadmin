<template>
    <div>
        <modal>
            <p class="lead">Please confirm your personal information. All fields are required.</p>
            <div class="row mt-5">
                <div class="col-6 offset-6">
                    <p class="font-weight-bold text-uppercase">Is your billing address the same as primary?</p>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sameAddressYes" v-model="sameAddress" :value="true" class="custom-control-input">
                        <label class="custom-control-label" for="sameAddressYes">Yes</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sameAddressNo" v-model="sameAddress" :value="false" class="custom-control-input">
                        <label class="custom-control-label" for="sameAddressNo">No</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">Primary Address <span>*</span></label>
                        <input v-model="addressData.primaryAddress" type="text" class="form-control form-control-sm">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">Apt/Suite</label>
                                <input v-model="addressData.primaryAptSuite" type="text" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">Country <span>*</span></label>
                                <select v-model="addressData.primaryCountry" class="form-control form-control-sm" @change="loadStates()">
                                    <option v-for="country in countries" :value="country.countrycode">{{ country.country }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">City/Town <span>*</span></label>
                                <input v-model="addressData.primaryCityTown" type="text" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">State/Province <span>*</span></label>
                                <select v-model="addressData.primaryStateProvince" class="form-control form-control-sm">
                                    <option v-for="state in primaryStates" :value="state.name" :checked="state.id === addressData.primaryStateProvince">{{ state.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Postal Code <span>*</span></label>
                        <input v-model="addressData.primaryPostalCode" type="text" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label class="control-label">Billing Address <span>*</span></label>
                        <input v-model="addressData.billingAddress" type="text" class="form-control form-control-sm" :readonly="sameAddress">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">Apt/Suite</label>
                                <input v-model="addressData.billingAptSuite" type="text" class="form-control form-control-sm" :readonly="sameAddress">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">Country <span>*</span></label>
                                <select v-model="addressData.billingCountry" class="form-control form-control-sm" :readonly="sameAddress">
                                    <option v-for="country in countries" :value="country.countrycode">{{ country.country }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">City/Town <span>*</span></label>
                                <input v-model="addressData.billingCityTown" type="text" class="form-control form-control-sm" :readonly="sameAddress">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">State/Province <span>*</span></label>
                                <select v-model="addressData.billingStateProvince" class="form-control form-control-sm">
                                    <option v-for="state in billingStates" :value="state.name" :checked="state.id === addressData.billingStateProvince">{{ state.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Postal Code <span>*</span></label>
                        <input v-model="addressData.billingPostalCode" type="text" class="form-control form-control-sm" :readonly="sameAddress">
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    import Modal from './../Modal';

    export default {
        name: 'AddressUser',
        components: {
            Modal
        },
        data() {
            return {
                countries: null,
                sameAddress: false,
                canContinue: false,
                primaryStates: null,
                billingStates: null,
            }
        },
        props: {
            addressData: {
                type: Object,
                required: true
            }
        },
        methods: {
            getCountries() {
                axios({
                    url: '/countries/json',
                    method: 'GET'
                }).then((res) => {
                    this.countries = res.data;
                }).catch((err) => {
                    //
                });
            },
            loadPrimaryStates() {
                axios({
                    url: `/states/json/${this.addressData.primaryCountry}`,
                    method: 'GET'
                }).then(res => {
                    this.primaryStates = res.data;
                }).catch(err => {
                    //
                });
            },
            loadBillingStates() {
                axios({
                    url: `/states/json/${this.addressData.billingCountry}`,
                    method: 'GET'
                }).then(res => {
                    this.billingStates = res.data;
                }).catch(err => {
                    //
                });
            },
            useSameAddress() {
                this.addressData.billingAddress = this.addressData.primaryAddress;
                this.addressData.billingAptSuite = this.addressData.primaryAptSuite;
                this.addressData.billingCountry = this.addressData.primaryCountry;
                this.addressData.billingCityTown = this.addressData.primaryCityTown;
                this.addressData.billingStateProvince = this.addressData.primaryStateProvince;
                this.addressData.billingPostalCode = this.addressData.primaryPostalCode;
            }
        },
        watch: {
            sameAddress: function (val) {
                if (val) {
                    this.useSameAddress();
                }
            },
            'addressData.primaryCountry': function () {
                this.loadPrimaryStates(this.addressData.primaryCountry);
            },
            'addressData.billingCountry': function () {
                this.loadBillingStates(this.addressData.billingCountry);
            },
        },
        mounted() {
            this.getCountries();
            this.loadPrimaryStates();
            this.loadBillingStates();
        }
    }
</script>
