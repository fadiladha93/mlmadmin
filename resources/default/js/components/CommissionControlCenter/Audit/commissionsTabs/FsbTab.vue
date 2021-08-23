<template>
    <div>
        <div v-if="totalData.length" class="row" style="margin-top: 25px;">
            <div class="col-12">
                <FsbTotalTable v-bind:data="totalData"/>
            </div>
        </div>
        <div v-if="totalData.length" class="row">
            <div class="col font-weight-normal input-wrap">
                <span>Order Number</span>
                <div class="mr-4">
                    <input type="text" class="form-control" v-model="orderNumber"/>
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
                <FsbOrdersTable v-bind:data="orderData"/>
            </div>
        </div>
        <div v-if="ordersData.length" class="row" style="margin-top: 25px;">
            <div class="col-12">
                <FsbOrdersTable v-bind:data="ordersData"/>
            </div>
        </div>
    </div>
</template>

<script>
    import BallLoader from '../../../partials/BallLoader'
    import FsbTotalTable from '../partials/FsbTotalTable'
    import FsbOrdersTable from '../partials/FsbOrdersTable'

    export default {
        name: 'AuditFsb',
        components: {
            BallLoader,
            FsbTotalTable,
            FsbOrdersTable,
        },
        props: {
            data: Object
        },
        created: function () {
            this.totalData = this.data.totalData;
            this.ordersData = this.data.ordersData;
        },
        data() {
            return {
                orderNumberErrorMessage: null,
                isOrderLoading: false,
                orderNumber: null,
                totalData: [],
                ordersData: [],
                orderData: [],
            }
        },
        methods: {
            onOrderSubmitBtnClick: function() {
                const self = this;
                self.orderData = [];

                if (self.isOrderLoading) {
                    return;
                }

                if (!self.orderNumber) {
                    self.orderNumberErrorMessage = 'Is required';
                    return;
                }

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
                            self.orderNumberErrorMessage = null;
                            //TODO remove test orderData
                            const orderData = [{
                                ordernumber: '123456',
                                orderdate: '09/20/19',
                                tsaassociated: 'TSA1234567',
                                username: 'travelguru',
                                amount: '47.95',
                                enrollmentPack: 'Coach class',
                                leg: 'right',
                                cv: '50',
                                qv: '47.95',
                                totalFsb: '49.95',
                            }];
                            self.orderData = orderData;
                //     },
                //     error: function (result) {
                //         self.orderNumberErrorMessage = result.responseJSON.error;
                //     },
                //     complete: function () {
                         self.isOrderLoading = false;
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
