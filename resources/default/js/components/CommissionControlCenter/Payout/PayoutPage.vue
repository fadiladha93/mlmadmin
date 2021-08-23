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
                            <input type="radio" class="custom-control-input" id="option2" v-model="commission" value="dual-team">
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
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option3" v-model="commission" value="tsb">
                            <label class="custom-control-label" for="option3">TSB</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option4" v-model="commission" value="promo">
                            <label class="custom-control-label" for="option4">PROMO</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="option9" v-model="commission" value="vibe">
                            <label class="custom-control-label" for="option9">VIBE</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <BallLoader v-if="isCommissionLoading" />
                        <button v-else class="btn btn-info btn-block" @click="onRunBtnClick">Run</button>
                    </div>
                </div>
            </div>
            <div>
                <DefaultTab v-bind:totalData="totalData"/>
            </div>
            <div v-if="totalData.length" class="row" style="margin-top: 25px;">
                <div class="col-12 text-center">
                    <button v-if="commission === 'fsb'" type="button" class="btn btn-info" @click="onSummaryClick()" style="min-width: 88px;margin-bottom: 7px;">Summary</button>
                    <button v-if="commission === 'fsb'" type="button" class="btn btn-info" @click="onDetailsClick()" style="min-width: 88px;margin-bottom: 7px;">Details</button>
                    <BallLoader v-if="isPayoutProcessing" />
                    <button type="button" class="btn btn-danger" @click="onPayoutBtnClick(null)" style="min-width: 88px;margin-bottom: 7px;">Process</button>
                </div>
            </div>
            <div class="details-table">
                <table class="table table-striped table-bordered table-hover table-checkable" id="commissionDetails">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Dist ID</th>
                        <th>Username</th>
                        <th>Amount</th>
                        <th>Level</th>
                        <th>Memo</th>
                    </tr>
                    </thead>
                </table>
            </div>

            <div class="summary-table">
                <table class="table table-striped table-bordered table-hover table-checkable" id="summaryDetails">
                    <thead>
                    <tr>
                        <th>Dist ID</th>
                        <th>Username</th>
                        <th>Estimated Earnings</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <VerificationModal v-if="showModal"
                               v-bind:errorMessage="verificationCodeErrorMessage"
                               @close="onModalCancelBtnClick"
                               @submit="onPayoutBtnClick">
            </VerificationModal>
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
    import VerificationModal from '../../partials/VerificationModal'
    import datePicker from 'vue-bootstrap-datetimepicker'
    import DefaultTab from './commissionsTabs/DefaultTab'
    import ProcessModal from '../partials/ProcessModal'

    export default {
        name: 'PayoutPage',
        components: {
            ModificationTypesButtons,
            BallLoader,
            VerificationModal,
            datePicker,
            DefaultTab,
            ProcessModal
        },
        data() {
            return {
                modificationType: 'payout',
                commissionPeriod: 'week',
                activeCommissionPeriod: 'week',
                commission: 'dual-team',
                activeCommission: 'dual-team',
                message: null,
                from: null,
                isCommissionTabsVisible: false,
                successMessage: null,
                errorMessage: null,
                fromDateErrorMessage: null,
                verificationCodeErrorMessage: '',
                isDateLoading: false,
                isCommissionLoading: false,
                isPayoutProcessing: false,
                totalData: [],
                showModal: false,
                options: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                },
                isVerifyToken: false,
                isDetails: false,
                isSummary: false
            }
        },
        mounted() {
            $('.details-table').hide();
            $('.summary-table').hide();
        },
        methods: {
            onSubmitPeriodBtnClick() {
                const self = this;
                self.setHeaders();

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

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/commission-control-center/commission-period',
                    data: JSON.stringify({
                        period: self.commissionPeriod,
                        fromDate: self.from,
                    }),
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        self.activeCommissionPeriod = activeCommissionPeriod;
                        self.commission = result.commission;
                        self.isCommissionTabsVisible = true;
                    },
                    error: function (result) {
                        self.fromDateErrorMessage = result.responseJSON.error;
                    },
                    complete: function () {
                        self.isDateLoading = false;
                    }
                });
            },
            onRunBtnClick() {
                const self = this;

                if (self.isCommissionLoading) {
                    return;
                }

                $('.details-table').hide();
                $('.summary-table').hide();

                self.errorMessage = null;
                self.isCommissionLoading = true;

                const activeCommission = self.commission;

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/commission-control-center/commission-details',
                    data: JSON.stringify({
                        period: self.commissionPeriod,
                        fromDate: self.from,
                        commission: self.commission
                    }),
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        self.activeCommission = activeCommission;

                        if (result.details) {
                            const details = result.details;

                            self.totalData = [
                                {
                                    startdate: details.startdate,
                                    closedate: details.closedate,
                                    tsapaid: details.tsapaid,
                                    averagecheck: details.averagecheck,
                                    highestcheck: details.highestcheck,
                                    highestchecktsa: details.highestchecktsa,
                                    totalcommission: details.totalcommission,
                                }
                            ];

                            self.from = details.startdate;
                        }

                        if (result.message) {
                            self.message = result.message;
                            self.totalData = []
                        }

                    },
                    error: function (result) {
                        self.errorMessage = result.responseJSON.error;
                    },
                    complete: function () {
                        self.isCommissionLoading = false;
                    }
                });
            },
            onPayoutBtnClick(verificationCode) {
                const self = this;

                self.verificationCodeErrorMessage = '';

                if (self.isVerifyToken && !verificationCode) {
                    self.verificationCodeErrorMessage = 'Verification code is required';
                    return;
                }

                if (self.isPayoutProcessing) {
                    return;
                }

                self.isPayoutProcessing = true;

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/commission-control-center/payout-commission',
                    data: JSON.stringify({
                        commission: self.commission,
                        verificationCode: verificationCode,
                        fromDate: self.from,
                        period: self.commissionPeriod
                    }),
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        if (result.verifyToken) {
                            self.isVerifyToken = result.verifyToken;
                            self.showModal = true;
                            return;
                        }
                        if (result.verifyTokenError) {
                            self.showModal = true;
                            self.verificationCodeErrorMessage = result.verifyTokenError;
                            return;
                        }
                        if (result.message) {
                            self.message = result.message;
                        }
                        self.showModal = false;
                    },
                    error: function (result) {
                        self.verificationCodeErrorMessage = result.responseJSON.error;
                    },
                    complete: function () {
                        self.isPayoutProcessing = false;
                    }
                });
            },
            onModalCancelBtnClick() {
                this.showModal = false;
                this.verificationCodeErrorMessage = '';
            },
            setHeaders() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json;',
                    }
                });
            },
            onDetailsClick() {
                const self = this;

                $('.details-table').show();
                $('.summary-table').hide();

                if (!self.isSummary) {
                    $('#commissionDetails').DataTable({
                        serverSide: true,
                        processing: true,
                        responsive: true,
                        searchDelay: 500,
                        order: [[0, "desc"]],
                        ajax: '/dt-commission-detail-post',
                        columns: [
                            {data: 'transaction_date'},
                            {data: 'distid'},
                            {data: 'username'},
                            {data: 'amount'},
                            {data: 'level'},
                            {data: 'memo'},
                        ]
                    });

                    self.isSummary = true;
                }
            },
            onSummaryClick() {
                const self = this;

                $('.summary-table').show();
                $('.details-table').hide();

                if (!self.isSummary) {
                    $('#summaryDetails').DataTable({
                        serverSide: true,
                        processing: true,
                        responsive: true,
                        searchDelay: 500,
                        order: [[0, "desc"]],
                        ajax: '/dt-approved-commission-summary',
                        columns: [
                            {data: 'distid'},
                            {data: 'username'},
                            {data: 'amount'},
                        ]
                    });

                    self.isSummary = true;
                }
            }
        }
    }
</script>
