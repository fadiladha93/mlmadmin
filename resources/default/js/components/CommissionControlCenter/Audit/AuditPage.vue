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
                        <div class="alert alert-danger">{{ errorMessage }}</div>
                    </div>
                </div>
            </transition>
            <ModificationTypesButtons v-bind:type="modificationType"/>
            <div class="row mt-5">
                <div class="col-md-3">
                    <date-picker v-model="from" :config="options" id="fromDatePicker" placeholder="From Date" autocomplete="off"></date-picker>
                    <div class="error-messages" v-if="fromDateErrorMessage" style="display: block" >{{ fromDateErrorMessage }}</div>
                </div>
                <div class="col-auto" style="display: flex; align-items: center; justify-content: center;">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="option7" v-model="commissionPeriod" value="week">
                        <label class="custom-control-label" for="option7">Week</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="option8" v-model="commissionPeriod" value="month">
                        <label class="custom-control-label" for="option8">Month</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <BallLoader v-if="isDateLoading" />
                    <button v-else class="btn btn-info" @click="onSubmitPeriodBtnClick">Submit</button>
                </div>
            </div>
            <div v-if="isCommissionTabsVisible" class="row" style="margin-top: 25px;">
                <div class="col-md-10 col-12" style="display: flex; align-items: center;">
                    <div v-if="activeCommissionPeriod === 'week'">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option1" v-model="commission" value="fsb">
                            <label class="custom-control-label" for="option1">FSB</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option2" v-model="commission" value="dual team">
                            <label class="custom-control-label" for="option2">DUAL TEAM</label>
                        </div>
                    </div>
                    <div v-else>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option5" v-model="commission" value="unilevel">
                            <label class="custom-control-label" for="option5">UNILEVEL</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option6" v-model="commission" value="leadership">
                            <label class="custom-control-label" for="option6">LEADERSHIP</label>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="isCommissionTabsVisible" class="row">
                <div class="col font-weight-normal input-wrap">
                    <span>TSA#/ USERNAME</span>
                    <div class="mr-4">
                        <input type="text" class="form-control" v-model="tsaNumber"/>
                        <div class="error-messages" v-if="tsaErrorMessage" style="display: block" >{{ tsaErrorMessage }}</div>
                    </div>
                    <div class="col-2">
                        <BallLoader v-if="isCommissionLoading" />
                        <button v-else class="btn btn-info m-btn--air btn-block" @click="onRunBtnClick">Run</button>
                    </div>
                </div>
            </div>
            <div v-if="isTabsVisible">
                <div v-if="activeCommission === 'dual team'">
                    <DualTeamTab v-bind:data="data"/>
                </div>
                <div v-if="activeCommission === 'fsb'">
                    <FsbTab v-bind:data="data"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ModificationTypesButtons from '../partials/ModificationTypesButtons'
    import BallLoader from '../../partials/BallLoader'
    import datePicker from 'vue-bootstrap-datetimepicker';
    import DualTeamTab from './commissionsTabs/DualTeamTab'
    import FsbTab from './commissionsTabs/FsbTab'

    export default {
        name: 'AuditPage',
        components: {
            ModificationTypesButtons,
            BallLoader,
            datePicker,
            DualTeamTab,
            FsbTab,
        },
        data() {
            return {
                modificationType: 'audit',
                commissionPeriod: 'week',
                activeCommissionPeriod: 'week',
                commission: 'dual team',
                activeCommission: 'dual team',
                isDateLoading: false,
                isCommissionLoading: false,
                isTabsVisible: false,
                isCommissionTabsVisible: false,
                from: null,
                tsaNumber: null,
                successMessage: null,
                errorMessage: null,
                tsaErrorMessage: null,
                fromDateErrorMessage: null,
                data: null,
                options: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                }
            }
        },
        methods: {
            onSubmitPeriodBtnClick() {
                const self = this;
                self.setHeaders();

                if (self.isDateLoading) {
                    return;
                }

                self.data = null;
                self.isDateLoading = true;
                self.fromDateErrorMessage = null;

                if (!self.from) {
                    self.isDateLoading = false;
                    self.fromDateErrorMessage = 'From Date is required';
                    return;
                }

                const activeCommissionPeriod = self.commissionPeriod;

                //TODO send self.from date and self.commissionPeriod
                // $.ajax({
                //     type: 'POST',
                //     url: baseUrl + '/commission-control-center/audit-period',
                //     data: JSON.stringify({
                //         period: self.commissionPeriod,
                //         fromDate: self.from,
                //     }),
                //     cache: false,
                //     dataType: 'json',
                //     success: function (result) {
                self.activeCommissionPeriod = activeCommissionPeriod;

                if (self.activeCommissionPeriod === 'week') {
                    self.commission = 'fsb';
                } else {
                    self.commission = 'unilevel';
                }

                self.isCommissionTabsVisible = true;
                // },
                // error: function (result) {
                //     self.fromDateErrorMessage = result.responseJSON.error;
                // },
                // complete: function () {
                self.isDateLoading = false;
                // }
                // });
            },
            onRunBtnClick() {
                const self = this;

                if (self.isCommissionLoading) {
                    return;
                }

                if (!self.tsaNumber) {
                    this.tsaErrorMessage = 'Is required';
                    return;
                }

                self.tsaErrorMessage = null;
                self.isCommissionLoading = true;

                const activeCommission = self.commission;

                //TODO send self.commission
                // $.ajax({
                //     type: 'POST',
                //     url: baseUrl + '/commission-control-center/audit-commission',
                //     data: JSON.stringify({
                //         fromDate: self.from,
                //         period: self.activeCommissionPeriod,
                //         commission: self.commission,
                //         tsaNumber: self.tsaNumber
                //     }),
                //     cache: false,
                //     dataType: 'json',
                //     success: function (result) {
                self.activeCommission = activeCommission;

                //TODO remove test data
                self.data = {
                    totalData: [{
                        name: 'Aldo Raine',
                        tsa: 'TSA1234567',
                        totalFsb: '49.95',
                    }],
                    ordersData: [{
                        ordernumber: '123456',
                        orderdate: '09/20/19',
                        tsaassociated: 'TSA1234567',
                        username: 'travelguru',
                        amount: '47.95',
                        enrollmentPack: 'Coach class',
                        leg: 'left',
                        cv: '40',
                        qv: '30',
                        totalFsb: '49.55',
                    },{
                        ordernumber: '123456',
                        orderdate: '09/20/19',
                        tsaassociated: 'TSA1234567',
                        username: 'travelguru',
                        amount: '47.95',
                        enrollmentPack: 'Coach class',
                        leg: 'right',
                        cv: '40',
                        qv: '30',
                        totalFsb: '49.55',
                    }]
                };
                self.isTabsVisible = true;
                // },
                // error: function (result) {
                //     self.tsaErrorMessage = result.responseJSON.error;
                // },
                // complete: function () {
                self.isCommissionLoading = false;
                // }
                // });
            },
            setHeaders() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json;',
                    }
                });
            },
        }
    }
</script>
