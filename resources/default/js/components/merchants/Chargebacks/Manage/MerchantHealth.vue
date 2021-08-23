<template>
    <div class="cb-row merchant-row">
        <div class="paper-box">
            <div class="paper-box_head">
                <h3 class="cb-sub-title">Merchants Health</h3>
            </div>
            <div class="paper-box_content">
                <div class="sub-container">
                    <div
                        class="cb-col"
                        v-for="(merchant, idx) in merchants"
                        :key="merchant.id"
                    >
                        <div class="box">
                            <h3 class="cb-sub-title">{{ merchant.name }}</h3>
                            <span
                                class="cb-badge bg-pink"
                                v-bind:class="{
                                    'bg-pink': idx === 0,
                                    'bg-green': idx === 1,
                                    'bg-orange': idx === 2
                                }"
                                >UNHEALTHY</span
                            >
                            <!-- # of CB's -->
                            <div class="cb-account-box">
                                <span class="label">Total Chargebacks</span>
                                <span class="value">{{
                                    formatNumber(totalChargebacks(merchant))
                                }}</span>
                                <div class="cb-progress">
                                    <div
                                        class="cb-progress-bar bg-green-red-gradient-h"
                                        :style="{
                                            width:
                                                scaleValue(
                                                    totalChargebacks(merchant)
                                                ) + '%'
                                        }"
                                    ></div>
                                </div>
                            </div>
                            <!-- $ of CB's -->
                            <div class="cb-account-box">
                                <span class="label"
                                    >Total Chargebacks Amount</span
                                >
                                <span class="value"
                                    >${{ formatNumber(totalCBAmount(merchant)) }}</span
                                >
                                <div class="cb-progress">
                                    <div
                                        class="cb-progress-bar bg-green-red-gradient-h"
                                        :style="{
                                            width:
                                                scaleValue(
                                                    totalCBAmount(merchant)
                                                ) + '%'
                                        }"
                                    ></div>
                                </div>
                            </div>
                            <!-- Total # of Transactions -->
                            <div class="cb-account-box">
                                <span class="label">Total Transactions</span>
                                <span class="value"
                                    >{{ totalTransactions(merchant) }}</span
                                >
                                <div class="cb-progress">
                                    <div
                                        class="cb-progress-bar bg-blue-gradient-h"
                                        :style="{
                                            width:
                                                scaleValue(
                                                    totalTransactions(merchant)
                                                ) + '%'
                                        }"
                                    ></div>
                                </div>
                            </div>
                            <!-- Total $ of Transactions -->
                            <div class="cb-account-box">
                                <span class="label">Total Sales</span>
                                <span class="value"
                                    >${{ formatNumber(totalSales(merchant)) }}</span
                                >
                                <div class="cb-progress">
                                    <div
                                        class="cb-progress-bar bg-blue-gradient-h"
                                        :style="{
                                            width:
                                                scaleValue(
                                                    formatNumber(totalSales(merchant))
                                                ) + '%'
                                        }"
                                    ></div>
                                </div>
                            </div>
                            <!-- % of CB of Transactions -->
                            <div class="cb-account-percentage">
                                <span class="label">% of Transactions</span>
                                <span class="value"
                                    >{{ formatNumber(percentTransactions(merchant)) }}%</span
                                >
                            </div>
                            <!-- % of CB of $ -->
                            <div class="cb-account-percentage">
                                <span class="label">% of Total Sales</span>
                                <span class="value"
                                    >{{ formatNumber(percentSales(merchant)) }}%</span
                                >
                            </div>

                            <button class="cb-btn" @click="showDetails(merchant)">Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "MerchantHealth",

    props: {
        cbData: {
            type: Array,
            required: true
        },
        merchants: {
            type: Array,
            required: true
        }
    },
    watch: {
        cbData: function(newVal) {
            this.data = newVal;
        }
    },
    methods: {
      formatNumber(number){
        let val = '-';
        try{ val = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") } catch(err) {}
        return val;
      },
      totalCBAmount: function(merchant) {
          const cbAmount = this.data
              .filter(d => d.merchant === merchant.name)
              .reduce((prev, cur) => {
                  return (parseFloat(prev) + parseFloat(cur.cb_amt)).toFixed(
                      2
                  );
              }, 0);

          return cbAmount;
        },
        totalTransactions: function(merchant) {
            const trans = this.data
                .filter(d => d.merchant === merchant.name)
                .reduce((prev, cur) => {
                  return prev + 1;//cur.status != 'Refunded'? 0 : 1;
                }, 0);

            return trans;
        },
        totalSales: function(merchant) {
            const sales = this.data
                .filter(d => d.merchant === merchant.name)
                .reduce((prev, cur) => {
                    return (
                        parseFloat(prev) + parseFloat(cur.tran_amt)
                    ).toFixed(2);
                }, 0);
            return sales;
        },
        totalChargebacks: function(merchant) {
            console.log(merchant);
            
            const chargeBacks = this.data.filter(
                d => d.merchant == merchant.name
            )
                .reduce((prev, cur) => {
                  return prev + 1;//cur.status != 'Refunded'? 0 : 1;
                }, 0);
                
            return chargeBacks;
        },
        percentSales: function(merchant) {
            const percent = parseFloat(
                (this.totalCBAmount(merchant) * 100) /
                    this.totalSales(merchant)
            ).toFixed(2);
            return percent > 0 ? percent : 0;
        },
        percentTransactions: function(merchant) {
            const percent = parseFloat(
                (this.totalChargebacks(merchant) * 100) /
                    this.totalTransactions(merchant)
            ).toFixed(2);
            return percent > 0 ? percent : 0;
        },
        scaleValue: function(value) {
            return value % 100;
        },
        showDetails: function(merchant){
          this.$emit('show-details', merchant);
        }
    },
    data() {
        return {
            data: []
        };
    },
    computed: {}
};
</script>

<style lang="scss">
@import "../assets/variables.scss";
.merchant-row {
    .sub-container {
        max-width: 1430px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        padding: 60px 15px;
        @media screen and (max-width: 767px) {
            flex-wrap: wrap;
            padding: 40px 15px 10px;
        }
    }
    .cb-badge {
        color: #fff;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 10px;
        margin-bottom: 8px;
        display: inline-block;
    }
    .cb-col {
        max-width: 242px;
        flex: 242px;
        padding-right: 15px;
        @media screen and (max-width: 767px) {
            max-width: 50%;
            flex: 50%;
            margin-bottom: 30px;
        }
        @media screen and (max-width: 479px) {
            max-width: 100%;
            flex: 100%;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-right: 0;
            &:last-child {
                border-bottom: 0;
            }
        }
    }
    .cb-sub-title {
        margin-bottom: 8px;
    }
    .cb-account-box {
        display: flex;
        flex-direction: column;
        .label {
            display: inline-block;
            color: $primaryText;
            margin-bottom: 5px;
            font-size: 20px;
            font-weight: 600;
            @media screen and (max-width: 1599px) {
                font-size: 18px;
            }
            @media screen and (max-width: 991px) {
                font-size: 16px;
            }
        }
        .value {
            display: inline-block;
            color: $primaryText;
            font-size: 30px;
            font-weight: 900;
            margin-bottom: 3px;
            @media screen and (max-width: 1599px) {
                font-size: 26px;
            }
            @media screen and (max-width: 991px) {
                font-size: 21px;
            }
        }
        .cb-progress {
            height: 24px;
            margin-bottom: 15px;
            background-color: #e4ebf7;
            @media screen and (max-width: 1599px) {
                height: 21px;
            }
            @media screen and (max-width: 991px) {
                height: 19px;
            }
            .cb-progress-bar {
                height: 100%;
                width: 75%;
                background-color: $lightBlue;
            }
        }
    }
    .cb-account-percentage {
        display: flex;
        flex-direction: column;
        .label {
            display: inline-block;
            color: $primaryText;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 500;
        }
        .value {
            display: inline-block;
            color: $lightBlue;
            font-size: 22px;
            font-weight: 900;
            margin-bottom: 8px;
        }
    }
}
</style>
