<template>
    <div>
        <div v-if="totalData.length" class="row" style="margin-top: 25px;">
            <div class="col-12">
                <DualTeamTotalTable v-bind:data="totalData"/>
            </div>
        </div>
        <div v-if="totalData.length" class="row">
            <div class="col font-weight-normal input-wrap">
                <span>Order Number</span>
                <div class="mr-4">
                    <input type="text" class="form-control" id="orderNumber"/>
                    <div class="error-messages" v-if="orderNumberErrorMessage" style="display: block" >{{ orderNumberErrorMessage }}</div>
                </div>
                <div>
                    <BallLoader v-if="isOrderLoading" />
                    <button v-else class="btn btn-info m-btn--air btn-block" @click="onOrderSubmitBtnClick">Submit</button>
                </div>
            </div>
        </div>
        <div v-if="orderData.length" class="row" style="margin-top: 25px;">
            <div class="col-12">
                <DualTeamOrdersTable v-bind:data="orderData"/>
            </div>
        </div>
        <div v-if="totalData.length" class="row mt-5">
            <div class="col-12">
                <div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="legDirection1" v-model="legDirection" value="left">
                        <label class="custom-control-label" for="legDirection1">Left</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="legDirection2" v-model="legDirection" value="right">
                        <label class="custom-control-label" for="legDirection2">Right</label>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="ordersDataLeft.length" class="row" style="margin-top: 25px;">
            <div class="col-12">
                <DualTeamOrdersTable v-if="legDirection === 'left'" v-bind:data="ordersDataLeft"/>
                <DualTeamOrdersTable v-else v-bind:data="ordersDataRight"/>
            </div>
        </div>
    </div>
</template>

<script>
    import BallLoader from '../../../partials/BallLoader'
    import DualTeamTotalTable from '../partials/DualTeamTotalTable'
    import DualTeamOrdersTable from '../partials/DualTeamOrdersTable'

    export default {
        name: 'AuditDualTeam',
        components: {
            BallLoader,
            DualTeamTotalTable,
            DualTeamOrdersTable,
        },
        props: {
            from: Array,
            isTsaVisible: Boolean
        },
        data() {
            return {
                commission: 'dual team',
                legDirection: 'left',
                orderNumberErrorMessage: null,
                isLoading: false,
                isOrderLoading: false,
                totalData: [],
                ordersDataLeft: [],
                ordersDataRight: [],
                orderData: [],
            }
        },
        methods: {
            onTsaSubmitBtnClick: function() {
                if (this.isLoading) {
                    return;
                }

                const tsaNumber = $('#tsaNumber')[0].value;

                if (!tsaNumber) {
                    this.tsaErrorMessage = 'Is required';
                    return;
                }

                const self = this;
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
                //         self.tsaErrorMessage = null;
                //         self.setTotalData(result.data);
                         self.setTotalData();
                //     },
                //     error: function (result) {
                //         self.tsaErrorMessage = result.responseJSON.error;
                //         self.totalData = [];
                //     },
                //     complete: function () {
                //         self.isLoading = false;
                //     }
                // });
            },
            setTotalData(data) {
                //TODO remove test data
                data = {
                    totalData: {
                        carryoverleft: '10,000',
                        carryoverright: '10,000',
                        currentpayleft: '10,000',
                        currentpayright: '10,000',
                        adjustmentsleft: '10,000',
                        adjustmentsright: '10,000',
                        totalvolumeleft: '10,000',
                        totalvolumeright: '10,000',
                        remainderleft: '10,000',
                        remainderright: '10,000',
                        paid: '20',
                        grossvolume: '123456',
                        total: '1,000,000',
                    },
                    ordersData: {
                        left: [{
                            ordernumber: '123456',
                            orderdate: '09/20/19',
                            tsaassociated: 'TSA1234567',
                            username: 'travelguru',
                            amount: '47.95',
                            cv: '50',
                            qv: '47.95',
                        },{
                            ordernumber: '123456',
                            orderdate: '09/20/19',
                            tsaassociated: 'TSA1234567',
                            username: 'travelguru',
                            amount: '47.95',
                            cv: '50',
                            qv: '47.95',
                        }],
                        right: [{
                            ordernumber: '123456',
                            orderdate: '09/20/19',
                            tsaassociated: 'TSA1234567',
                            username: 'travelguru',
                            amount: '47.95',
                            cv: '50',
                            qv: '47.95',
                        },{
                            ordernumber: '123456',
                            orderdate: '09/20/19',
                            tsaassociated: 'TSA1234567',
                            username: 'travelguru',
                            amount: '47.95',
                            cv: '50',
                            qv: '47.95',
                        },{
                            ordernumber: '123456',
                            orderdate: '09/20/19',
                            tsaassociated: 'TSA1234567',
                            username: 'travelguru',
                            amount: '47.95',
                            cv: '50',
                            qv: '47.95',
                        }]
                    }
                };
                const rowTotalData = this.mapTotalDataToTable(data.totalData);
                const ordersDataLeft = [];
                const ordersDataRight = [];
                data.ordersData.left.map((order) => {
                    const rowData = this.mapOrderToTable(order);
                    ordersDataLeft.push(rowData);
                });
                data.ordersData.right.map((order) => {
                    const rowData = this.mapOrderToTable(order);
                    ordersDataRight.push(rowData);
                });
                this.totalData = [rowTotalData];
                this.ordersDataLeft = ordersDataLeft;
                this.ordersDataRight = ordersDataRight;
                this.isLoading = false;
                this.tsaErrorMessage = null;
            },
            mapTotalDataToTable(data) {
                return {
                    carryoverleft: data.carryoverleft,
                    carryoverright: data.carryoverright,
                    currentpayleft: data.currentpayleft,
                    currentpayright: data.currentpayright,
                    adjustmentsleft: data.adjustmentsleft,
                    adjustmentsright: data.adjustmentsright,
                    totalvolumeleft: data.totalvolumeleft,
                    totalvolumeright: data.totalvolumeright,
                    remainderleft: data.remainderleft,
                    remainderright: data.remainderright,
                    paid: data.paid,
                    grossvolume: data.grossvolume,
                    total: data.total,
                }
            },
            setDistributorsData(distributors) {
                const distributorsArray = [];
                distributors.map((distributor) => {
                    const rowData = this.mapUserToTable(distributor);
                    distributorsArray.push(rowData);
                });
                this.distributors = distributorsArray;
            },
            mapOrderToTable(data) {
                return {
                    ordernumber: data.ordernumber,
                    orderdate: data.orderdate,
                    tsaassociated: data.tsaassociated,
                    username: data.username,
                    amount: data.amount,
                    cv: data.cv,
                    qv: data.qv,
                }
            },
            onOrderSubmitBtnClick: function() {
                if (this.isOrderLoading) {
                    return;
                }

                const orderNumber = $('#orderNumber')[0].value;

                if (!orderNumber) {
                    this.orderNumberErrorMessage = 'Is required';
                    return;
                }

                const self = this;
                self.isOrderLoading = true;
                self.setHeaders();

                // $.ajax({
                //     type: 'POST',
                //     url: baseUrl + '/commission-control-center/audit/order',
                //     data: JSON.stringify({
                //         order: orderNumber,
                //     }),
                //     cache: false,
                //     dataType: 'json',
                //     success: function (result) {
                //         self.orderNumberErrorMessage = null;
                //         self.setOrderData(result.data);
                         self.setOrderData();
                //     },
                //     error: function (result) {
                //         self.orderNumberErrorMessage = result.responseJSON.error;
                //         self.orderData = [];
                //     },
                //     complete: function () {
                //         self.isOrderLoading = false;
                //     }
                // });
            },
            setOrderData(orderData) {
                //TODO remove test orderData
                orderData = {
                    ordernumber: '123456',
                    orderdate: '09/20/19',
                    tsaassociated: 'TSA1234567',
                    username: 'travelguru',
                    amount: '47.95',
                    cv: '50',
                    qv: '47.95',
                };
                this.orderData = [this.mapOrderToTable(orderData)];
                this.isOrderLoading = false;
                this.orderNumberErrorMessage = null;
            },
            onPeriodChange() {
                this.from = $('#fromDatePicker').val();
            },
            onSummaryBtnClick() {
            },
            onDetailsBtnClick() {
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
