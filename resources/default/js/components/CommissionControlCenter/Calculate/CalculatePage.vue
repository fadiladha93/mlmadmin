<template>
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Commission Control Center
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <transition name="fade">
                <div class="row" v-if="successMessage">
                    <div class="col-md-6">
                        <div class="alert alert-success">{{ successMessage }}</div>
                    </div>
                </div>
            </transition>
            <transition name="fade">
                <div class="row" v-if="errorMessage">
                    <div class="col-md-6">
                        <div class="alert alert-danger" v-html="errorMessage"></div>
                    </div>
                </div>
            </transition>
            <ModificationTypesButtons v-bind:type="modificationType"/>
            <div class="row mt-5">
                <div class="col-md-3">
                    <date-picker v-model="from" :config="options" id="fromDatePicker" placeholder="From Date"
                                 autocomplete="off"></date-picker>
                    <div class="error-messages" v-if="fromDateErrorMessage" style="display: block">{{
                        fromDateErrorMessage }}
                    </div>
                </div>
                <div class="col-auto" style="display: flex; align-items: center; justify-content: center;">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="option7" v-model="commissionPeriod"
                               value="week">
                        <label class="custom-control-label" for="option7">Week</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="option8" v-model="commissionPeriod"
                               value="month">
                        <label class="custom-control-label" for="option8">Month</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <BallLoader v-if="isDateLoading"/>
                    <button v-else class="btn btn-info" @click="onSubmitPeriodBtnClick">Submit</button>
                </div>
            </div>
            <div v-if="isCommissionTabsVisible" class="row" style="margin-top: 25px;">
                <div class="col-md-10 col-12" style="display: flex; align-items: center;">
                    <div v-if="activeCommissionPeriod === 'week'">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option1" v-model="commission"
                                   value="fsb">
                            <label class="custom-control-label" for="option1">FSB</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option2" v-model="commission"
                                   value="dual-team">
                            <label class="custom-control-label" for="option2">DUAL TEAM</label>
                        </div>
                    </div>
                    <div v-else>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option5" v-model="commission"
                                   value="unilevel">
                            <label class="custom-control-label" for="option5">UNILEVEL</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option6" v-model="commission"
                                   value="leadership">
                            <label class="custom-control-label" for="option6">LEADERSHIP</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option3" v-model="commission"
                                   value="tsb">
                            <label class="custom-control-label" for="option3">TSB</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option4" v-model="commission"
                                   value="promo">
                            <label class="custom-control-label" for="option4">PROMO</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option9" v-model="commission"
                                   value="vibe">
                            <label class="custom-control-label" for="option9">VIBE</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <BallLoader v-if="isCommissionLoading"/>
                        <button v-else class="btn btn-info btn-block" @click="onRunBtnClick">Run</button>
                    </div>
                </div>
                <div class="row col-md-12 col-12" v-if="['tsb', 'promo', 'vibe'].indexOf(commission) !== -1">
                    <input type="file" class="form-control-file mb-3" id="tsb_commissions_csv" name="tsb_commissions_csv"
                           v-on:change="onTsbFileInputChange">
                </div>
                <div class="row col-md-12 col-12" v-if="commission === 'promo'">
                    <label>Promotion:</label>
                    <input type="text" class="form-control-file ml-3" v-model="promo" style="width: 12.5vw;">
                </div>
            </div>
            <div>
                <DefaultTab v-bind:totalData="totalData"/>
            </div>
            <ConfirmModal v-if="isConfirmRecalculate"
                          @close="isConfirmRecalculate = false"
                          @submit="onRunBtnClick">
                <div slot="body" class="message">
                    <div class="info-value">The commission for this period was already calculated. Do you want to
                        recalculate it? All data will be overwritten.
                    </div>
                </div>
            </ConfirmModal>
            <ConfirmModal v-if="isConfirmAction"
                          @close="isConfirmAction = null"
                          @submit="onRunBtnClick">
                <div slot="body" class="message">
                    <div class="info-value">{{confirmMessage}}</div>
                </div>
            </ConfirmModal>
            <ProcessModal v-if="message"
                          @close="message = false">
                <div slot="body" class="message">
                    <div class="info-value">{{message}}</div>
                </div>
                <div slot="footer">
                    <button type="button" class="btn btn-secondary btn-sm" @click="message = null">ОК</button>
                </div>
            </ProcessModal>
        </div>
    </div>
</template>

<script>
    import ModificationTypesButtons from '../partials/ModificationTypesButtons'
    import BallLoader from '../../partials/BallLoader'
    import datePicker from 'vue-bootstrap-datetimepicker'
    import DefaultTab from './commissionsTabs/DefaultTab'
    import ConfirmModal from '../partials/ConfirmModal'
    import ProcessModal from '../partials/ProcessModal'

    export default {
        name: 'CalculatePage',
        components: {
            ModificationTypesButtons,
            BallLoader,
            datePicker,
            DefaultTab,
            ConfirmModal,
            ProcessModal
        },
        data() {
            return {
                modificationType: 'calculate',
                commissionPeriod: 'week',
                activeCommissionPeriod: 'week',
                commission: 'dual-team',
                activeCommission: 'dual team',
                from: null,
                isCommissionTabsVisible: false,
                successMessage: null,
                errorMessage: null,
                fromDateErrorMessage: null,
                isDateLoading: false,
                isCommissionLoading: false,
                totalData: [],
                options: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                },
                isConfirmAction: false,
                isConfirmRecalculate: false,
                confirmMessage: null,
                message: null,
                tsbFileUpload: null,
                promo: null
            }
        },
        methods: {
            onTsbFileInputChange(event) {
                const self = this;
                var files = event.target.files || event.dataTransfer.files;

                if (!files.length) {
                    return;
                }

                var file = files[0];

                if (!file.name.toLowerCase().endsWith('.csv')) {
                    self.errorMessage = 'You need to upload a .csv file!';
                    event.target.value = '';
                    return;
                }

                self.errorMessage = null;
                self.tsbFileUpload = file;
            },
            onSubmitPeriodBtnClick() {
                const self = this;

                if (self.isDateLoading) {
                    return;
                }

                self.totalData = [];
                self.isDateLoading = true;
                self.fromDateErrorMessage = null;

                if (!self.from) {
                    self.isDateLoading = false;
                    self.fromDateErrorMessage = 'From Date is required';
                    return;
                }

                const activeCommissionPeriod = self.commissionPeriod;
                const url = baseUrl + '/commission-control-center/commission-period';
                const data = {
                    period: self.commissionPeriod,
                    fromDate: self.from
                };

                const options = {
                    responseType: 'json'
                };

                axios.post(url, data, options).then(function(response) {
                    var result = response.data;
                    self.activeCommissionPeriod = activeCommissionPeriod;
                    self.commission = result.commission;
                    self.isCommissionTabsVisible = true;
                }).catch(function(error) {
                    self.fromDateErrorMessage = error.response.data.error;
                }).finally(function() {
                    self.isDateLoading = false;
                });
            },
            onRunBtnClick() {
                const self = this;

                if (self.isCommissionLoading) {
                    return;
                }

                if (self.commission === 'tsb' && self.tsbFileUpload === null) {
                    self.errorMessage = 'You must upload a CSV File to calculate TSB';
                    return;
                }

                if (self.commission === 'promo' && self.tsbFileUpload === null) {
                    self.errorMessage = 'You must upload a CSV File for Promo Commissions';
                    return;
                }

                if (self.commission === 'vibe' && self.tsbFileUpload === null) {
                    self.errorMessage = 'You must upload a CSV File for Vibe Commissions';
                    return;
                }


                if (self.commission === 'promo' && self.promo === null) {
                    self.errorMessage = 'You must specify the name of the promo';
                    return;
                }

                    self.errorMessage = null;
                self.isCommissionLoading = true;

                const formData = new FormData();
                const url = baseUrl + '/commission-control-center/calculate-commission';
                const options = {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                };

                const params = {
                    'period': self.commissionPeriod,
                    'fromDate': self.from,
                    'commission': self.commission,
                    'isConfirmAction': self.isConfirmAction,
                    'isConfirmRecalculate': self.isConfirmRecalculate
                };

                const fileCommissionTypes = [
                    'tsb',
                    'promo',
                    'vibe'
                ];

                if (fileCommissionTypes.indexOf(self.commission) != -1) {
                    formData.append('file', self.tsbFileUpload);
                }

                if (self.commission == 'promo') {
                    formData.append('promo', self.promo);
                }

                formData.append('params', JSON.stringify(params));

                axios.post(url, formData, options).then(function (response) {
                    const result = response.data;

                    if (result.error === 1) {
                        self.errorMessage = result.msg;
                        return;
                    }

                    if (result.isConfirmAction) {
                        self.isConfirmAction = result.isConfirmAction;
                        self.confirmMessage = result.message;
                        return;
                    }

                    if (result.isConfirmRecalculate) {
                        self.isConfirmRecalculate = result.isConfirmRecalculate;
                        return;
                    }

                    if (result.message) {
                        self.message = result.message;
                    }

                    self.isConfirmAction = false;
                    self.isConfirmRecalculate = false;
                    self.confirmMessage = null;
                }).catch(function (error) {
                    self.errorMessage = error.response.data.error;
                }).finally(function () {
                    self.isCommissionLoading = false;
                });
            }
        },
        watch: {
            commission: function (value, oldValue) {
                const self = this;
                // If you change tabs from TSB to dual team or FSB, it shouldn't retain the file (file input does not)
                self.tsbCommissionCsv = null;
                // Clear error message.
                self.errorMessage = null;
            }
        },
    }
</script>
