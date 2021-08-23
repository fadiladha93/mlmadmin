<template>
    <div>
        <div v-if="totalData.length" class="row" style="margin-top: 25px;">
            <div class="col-12">
                <FsbTotalTable v-bind:data="totalData"/>
            </div>
        </div>
        <div class="row mt-5">
            <div class="font-weight-normal input-wrap mt-0" style="padding: 0 15px">
                <span style="min-width: 170px">Adjustment FSB %</span>
                <input class="form-control" id="percent" v-model="adjustmentFsbPercent" name="adjustmentFsbPercent" type="number" min="0" max="100" style="width: 150px">
            </div>
        </div>
        <div class="row">
            <div class="font-weight-normal input-wrap mt-5" style="padding: 0 15px">
                <span style="min-width: 170px">Commission Adjustment</span>
                <input class="form-control" id="commissionAdjustment" v-model="commissionAdjustment" name="commissionAdjustment" style="width: 150px">
            </div>
            <div class="font-weight-normal input-wrap mt-5" style="padding: 0 15px">
                <span>Commission Adjustment Memo</span>
                <input class="form-control" id="commissionAdjustmentMemo" v-model="commissionAdjustmentMemo" name="commissionAdjustmentMemo">
            </div>
        </div>
        <div class="row mt-5 pl-3">
            <BallLoader v-if="isLoading" />
            <button v-else class="btn btn-info mr-4" @click="onSubmitBtnClick">Submit</button>
            <button class="btn btn-secondary" @click="onResetBtnClick">Reset</button>
        </div>
        <div class="row mt-5" style="border-top: 1px solid #eaebee; padding-bottom: 10px; margin-left: 0; margin-right: 0;">
            <div class="col font-weight-bold input-wrap" style="justify-content: center;">
                <span>Adjusted Commission</span>
                <div class="mr-4">
                    <input type="text" class="form-control" id="adjustedCommission" style="width: 150px"/>
                </div>
                <div>
                    <button class="btn btn-danger m-btn--air btn-block" @click="onAdjustBtnClick">Adjust</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import BallLoader from '../../../partials/BallLoader'
    import FsbTotalTable from '../partials/FsbTotalTable'

    export default {
        name: 'AdjustmentFsb',
        components: {
            BallLoader,
            FsbTotalTable,
        },
        props: {
            totalData: Array
        },
        data() {
            return {
                commission: 'fsb',
                isLoading: false,
                //isModalVisible: false,
                adjustmentFsbPercent: null,
                commissionAdjustment: null,
                commissionAdjustmentMemo: null,
            }
        },
        methods: {
            onSubmitBtnClick() {
            },
            onResetBtnClick() {
                this.adjustmentFsbPercent = null;
                this.commissionAdjustment = null;
                this.commissionAdjustmentMemo = null;
            },
            onAdjustBtnClick() {
                this.isModalVisible = true;
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
