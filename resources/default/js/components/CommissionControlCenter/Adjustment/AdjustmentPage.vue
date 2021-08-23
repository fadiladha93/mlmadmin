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
                <div class="font-weight-normal input-wrap mt-0" style="padding: 0 15px">
                    <span>TSA#/ USERNAME</span>
                    <div style="position: relative;">
                        <input type="text" class="form-control" v-model="tsaNumber" id="tsaNumber" style="width: 150px;"/>
                        <div class="error-messages" v-if="tsaErrorMessage" style="display: block;" >{{ tsaErrorMessage }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <date-picker v-model="from" :config="options" id="fromDatePicker" placeholder="From Date" autocomplete="off"></date-picker>
                    <div class="error-messages" v-if="fromDateErrorMessage" style="display: block;" >{{ fromDateErrorMessage }}</div>
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
                    <div class="col-2">
                        <BallLoader v-if="isCommissionLoading" />
                        <button v-else class="btn btn-info btn-block" @click="onRunBtnClick">Run</button>
                    </div>
                </div>
            </div>
            <div v-if="commission === 'dual team'">
            </div>
            <div v-if="commission === 'fsb'">
                <FsbTab v-bind:totalData="totalData"/>
            </div>
            <div v-if="commission === 'unilevel'">
            </div>
            <div v-if="commission === 'leadership'">
            </div>
        </div>
    </div>
</template>

<script>
    import ModificationTypesButtons from '../partials/ModificationTypesButtons'
    import BallLoader from '../../partials/BallLoader'
    import datePicker from 'vue-bootstrap-datetimepicker';
    import FsbTab from './commissionsTabs/FsbTab'

    export default {
        name: 'AdjustmentPage',
        components: {
            ModificationTypesButtons,
            BallLoader,
            datePicker,
            FsbTab,
        },
        data() {
            return {
                modificationType: 'adjustment',
                commissionPeriod: 'week',
                activeCommissionPeriod: 'week',
                commission: 'dual team',
                activeCommission: 'dual team',
                isCommissionTabsVisible: false,
                successMessage: null,
                errorMessage: null,
                tsaErrorMessage: null,
                tsaNumber: null,
                fromDateErrorMessage: null,
                isDateLoading: false,
                isCommissionLoading: false,
                totalData: [],
                from: null,
                options: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                }
            }
        },
        methods: {
            onRunBtnClick() {
                const self = this;

                if (self.isLoading) {
                    return;
                }

                self.tsaErrorMessage = '';
                self.fromDateErrorMessage = '';

                if (!self.from) {
                    self.fromDateErrorMessage = self.from ? null : 'From Date is required';
                }

                if (!self.tsaNumber) {
                    self.tsaErrorMessage = self.tsaNumber ? null : 'Is required';
                }

                if (self.tsaErrorMessage || self.fromDateErrorMessage) {
                    return;
                }

                self.isLoading = true;
                self.setHeaders();



                // $.ajax({
                //     type: 'POST',
                //     url: baseUrl + '/commission-control-center/audit/tsa',
                //     data: JSON.stringify({
                //         tsa: tsaNumber,
                //     }),
                //     cache: false,
                //     dataType: 'json',
                //     success: function (result) {
                //TODO use dataPeriod from result
                            self.dataPeriod = self.commissionPeriod;
                            if (self.dataPeriod === 'week') {
                                self.commission = 'fsb';
                            } else {
                                self.commission = 'unilevel';
                            }

                            //TODO remove test data
                            self.totalData = [
                                {
                                    name: 'Chip Galnes',
                                    sponsorname: 'Aldo Raine',
                                    sponsorid: 'TSA1234567',
                                    totalvolume: '227.50',
                                    fsbpersentage: '10',
                                    total: '22.75',
                                }
                            ];

                //         self.tsaErrorMessage = null;
                //         self.setTotalData(result.data);
                //self.setTotalData();
                //     },
                //     error: function (result) {
                //         self.tsaErrorMessage = result.responseJSON.error;
                //         self.totalData = [];
                //     },
                //     complete: function () {
                         self.isLoading = false;
                //     }
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
