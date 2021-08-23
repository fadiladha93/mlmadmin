<template>
    <div id="app">
        <div class="main-content">
            <div class="main-container">
                <div class ="cb-row account-row">
                  <cb-stats-card class="cb-col-3" title="Total Sales" :value="`$${formatNumber(totalSales)}`" />
                  <cb-stats-card class="cb-col-3" title="Total Trxns" :value="`${formatNumber(totalTrans)}`" />
                  <cb-stats-card class="cb-col-4" title="Total Chgbks" :value="`${formatNumber(totalCbs)}`" />
                  <cb-stats-card class="cb-col-4" title="% CB to Sales" :value="`${formatNumber(percentSales)}%`" color="red" />
                  <cb-stats-card class="cb-col-4" title="% CB to Trxns" :value="`${formatNumber(percentTransactions)}%`" color="red" />
                  <cb-stats-card class="cb-col-3" title="Total CB Fees" :value="`$${formatNumber(totalCBFees)}`" color="red" />
                </div>
                <div class="cb-row chart-row">
                  <div class="paper-box">
                    <div class="paper-box_head">
                      <h3 class="cb-sub-title">Chargeback Overview</h3>
                    </div>
                    <div class="paper-box_content">
                      <div class="cb-filter-group">
                        <div class="cb-filter">
                          <div class="cb-filter_btn no-border">
                            <span class="cb-filter_text" @click="monthHandler()">{{selectedMonth}}</span>
                            <span class="arrow-icon"></span>
                          </div>
                          <ul class="cb-filter_list" v-if="showMonths">
                            <li
                              v-for="m in months"
                              :key="m"
                              class="cb-filter_item"
                              @click="selectMonth(m)"
                            >{{m}}</li>
                          </ul>
                        </div>

                        <div class="cb-filter data-picker">
                          <VueCtkDateTimePicker
                            buttonColor="#0892d0"
                            color="#1b2134"
                            id="RangeDatePicker"
                            range
                            v-model="dateRangeValue"
                            @validate="applyFilter($event)"
                          />
                          <div class="cb-filter_btn">
                            <span class="cb-filter_text">Range</span>

                            <span class="arrow-icon"></span>
                          </div>
                        </div>
                        <!-- <div class="cb-filter">
                          <div class="cb-filter_btn bg-blue">
                            <span class="cb-filter_text">Last</span>
                            <span class="arrow-icon white"></span>
                          </div>
                        </div>
                        <div class="cb-filter">
                          <div class="cb-filter_btn bg-blue">
                            <span class="cb-filter_text">Current</span>
                            <span class="arrow-icon white"></span>
                          </div>
                        </div> -->

                        <button class="cb-btn" v-on:click="dateRangeValue = [new Date(), new Date()]; applyFilter()">Today</button>
                      </div>
                      <cb-overview :cbData="filteredData" />
                    </div>
                  </div >
                </div>

                <cb-merchant-health @show-details="showDetails" :cbData="filteredData" :merchants="merchants" />
                <div v-if="showingSummary" class="text-center">

                  <div class="lds-roller "><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>

                <cb-summary v-if="showSummary && !showingSummary" :cbData="filteredData" :columns="columns"/>
            </div>
        </div>
    </div>
</template>

<script>
import * as axios from 'axios';
export default {
    data: () => {
        return {
            showingSummary: false,
            showSummary: false,
            cbData: [],
            filteredData: [],
            columns: [],
            columnMappings: {
              "transaction_id": "Transaction Id",
            },
            dateRangeValue: null,
            months: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
            ],
            showMonths: false,
            selectedMonth: "Month",
            merchants: []
        };
    }, 
    mounted() {
        let dtJSON = `[["5408974327","No Cardholder Authorization","Auto Represented Chargeback (5613)","10590899","T1","6/4/20","6/11/20","7","Tuesday June 16, 2020","135.2","100.12 (USD)","MASTERCARD","516075","5613","ABDELMAJID SAIRE","1"],["5408974327","No Cardholder Authorization","Instant Represented Chargeback (5613)","10603039","T1","6/4/20","6/14/20","10","Thursday June 18, 2020","135.2","100.12 (USD)","MASTERCARD","516075","5613","ABDELMAJID SAIRE","1"],["5348473571","Other Fraud Card Absent","Chargeback Notification (1772)","10575549","T1","5/7/20","6/4/20","28","Tuesday June 9, 2020","848.95","913.84 (TWD)","VISA","428440","1772","ABDOU SOUMARE","1"],["5360694213","No Cardholder Authorization","Chargeback Notification (8711)","10573821","T1","5/13/20","6/3/20","21","Monday June 8, 2020","549","549 (USD)","MASTERCARD","544612","8711","ABRAHAM GUENTHER KRAHN","1"],["5303745039","No Cardholder Authorization","Chargeback Notification (8711)","10573817","T1","4/17/20","6/3/20","47","Monday June 8, 2020","299.95","299.95 (USD)","MASTERCARD","544612","8711","ABRAHAM KRAHN","1"],["5338870670","No Cardholder Authorization","Chargeback Notification (8711)","10573823","T1","5/2/20","6/3/20","32","Monday June 8, 2020","299","299 (USD)","MASTERCARD","544612","8711","ABRAHAM KRAHN","1"],["5368076711","No Cardholder Authorization","Chargeback Notification (8711)","10573819","T1","5/16/20","6/3/20","18","Monday June 8, 2020","99.95","99.95 (USD)","MASTERCARD","544612","8711","ABRAHAM KRAHN","1"],["5399165014","Other Fraud Card Absent","Chargeback Notification (7488)","10631567","T1","5/31/20","6/26/20","26","Wednesday July 1, 2020","270.1","325.16 (EUR)","VISA","474836","7488","AITEN SALIH","1"],["5415600061","Other Fraud Card Absent","Auto Represented Chargeback (7759)","10613787","T1","6/7/20","6/19/20","12","Wednesday June 24, 2020","2157.14","103.91 (MXN)","VISA","477214","7759","ALEJANDRO TAME","1"],["5248120108","No Cardholder Authorization","Auto Represented Chargeback (7159)","10611221","T1","3/18/20","6/18/20","92","Tuesday June 23, 2020","299.95","299.95 (USD)","MASTERCARD","530514","7159","ALESSANDRO MOCCI","1"],["5248120108","No Cardholder Authorization","Instant Represented Chargeback (7159)","10620399","T1","3/18/20","6/21/20","95","Thursday June 25, 2020","299.95","299.95 (USD)","MASTERCARD","530514","7159","ALESSANDRO MOCCI","1"],["5396746748","Other Fraud Card Absent","Chargeback Notification (1463)","10590633","T1","5/30/20","6/11/20","12","Tuesday June 16, 2020","764.47","959.09 (XXX)","VISA","455701","1463","ALEXANDRE PÉPPI","1"],["5375574664","Other Fraud Card Absent","Chargeback Notification (3543)","10584019","T1","5/20/20","6/7/20","18","Thursday June 11, 2020","848.95","848.95 (USD)","VISA","473702","3543","ANDREW PIETERS","1"],["5201810041","Cancelled Merchandise/Services","Chargeback Notification (4429)","10622721","T1","2/25/20","6/23/20","119","Friday June 26, 2020","99.95","99.95 (USD)","VISA","420767","4429","ANDREY RUDENKO","1"],["5259667082","Cancelled Merchandise/Services","Chargeback Notification (4429)","10622723","T1","3/25/20","6/23/20","90","Friday June 26, 2020","99.95","99.95 (USD)","VISA","420767","4429","ANDREY RUDENKO","1"],["5321415557","Cancelled Merchandise/Services","Chargeback Notification (4429)","10622785","T1","4/25/20","6/23/20","59","Friday June 26, 2020","99.95","99.95 (USD)","VISA","420767","4429","ANDREY RUDENKO","1"],["5240492013","Other Fraud Card Absent","Chargeback Notification (1247)","10619705","T1","3/14/20","6/21/20","99","Thursday June 25, 2020","49.95","49.95 (USD)","VISA","499953","1247","ANGELICA GRANT","1"],["5351548815","Other Fraud Card Absent","Chargeback Notification (3310)","10580219","T1","5/8/20","6/5/20","28","Wednesday June 10, 2020","49.95","53.47 (JMD)","VISA","430390","3310","ANSERD KERR","1"],["5396787304","Other Fraud Card Absent","Chargeback Notification (1463)","10590629","T1","5/30/20","6/11/20","12","Tuesday June 16, 2020","764.47","959.09 (XXX)","VISA","455701","1463","ANTHONY DADET","1"],["",null,null,null,null,null,null,null,null,null,null,null,null,null,null,null]]`;
        // this.cbData = JSON.parse(dtJSON);
         axios.get("/chargeback/dataCsv")
          .then(text => {
            const parsedData = this.csvJSON(text.data);
            this.cbData = JSON.parse(parsedData);
            this.filteredData = JSON.parse(parsedData);
            this.columns = this.getColumnNames(this.cbData);
          });
          axios.get('/chargeback/merchants')
            // get data
            .then(x => {
                this.merchants = x.data;
            })
            .catch(err => {
                console.log(err);
            });
    },
    methods: {
      loadMerchantData(merchant){
          return axios.get("/chargeback/dataCsv", { params: { merchant } })
      },
      showDetails: function(merchant){
        this.showingSummary = true;
        this.loadMerchantData(merchant).then(res => {
          this.showingSummary = false;
          this.showSummary = true;
          console.log(res);
            
            const parsedData = this.csvJSON(res.data);
            this.cbData = JSON.parse(parsedData);
            this.filteredData = JSON.parse(parsedData);
            this.columns = this.getColumnNames(this.cbData);
        })
        .catch(err =>{
          this.showingSummary = false;
        });
      },
      csvJSON: function(csv) {
        let lines = csv.split("\n");
        console.log(lines);
        
        let result = [];
        let headers = lines[0].split(",");
        for (var i = 1; i < lines.length; i++) {
          let obj = {};
          let currentline = lines[i].split(/,(?=(?:[^"]*"[^"]*")*(?![^"]*"))/);
          for (let j = 0; j < headers.length; j++) {
            obj[headers[j]] = currentline[j].toString().replace(/"/g, "");
          }
          result.push(obj);
        }
        return JSON.stringify(result); //JSON
      },
      getColumnNames: function(data) {
        const keys = Object.keys(data[0]);
        const columns = keys.map(k => {
          const obj = {
            title: this.columnMappings[k] ? this.columnMappings[k] : k.toString().replace('_', ' '),
            name: k,
            sortField: k
          };
          return obj;
        });

        return columns;
      },
      // eslint-disable-next-line no-unused-vars
      applyFilter: function(e) {
        const startDate = new Date(this.dateRangeValue.start);
        const endDate = new Date(this.dateRangeValue.end);
        const dataToFilter = this.cbData;
          console.log(dataToFilter);
        const result = dataToFilter.filter(d => {
          const time = new Date(d.cb_date);
          return startDate < time && time < endDate;
        });

        this.filteredData = [...result];
      },
      monthHandler: function() {
        this.showMonths = !this.showMonths;
      },
      selectMonth: function(m) {
        this.selectedMonth = m;
        this.showMonths = !this.showMonths;
      },
      formatNumber(number){
        let val = '-';
        try{ val = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") } catch(err) {}
        return val;
      }
    },
    computed: {
        totalCBAmount: function() {
            const cbAmount = this.filteredData
                .reduce((prev, cur) => {
                    return (parseFloat(prev) + parseFloat(cur.cb_amt)).toFixed(
                        2
                    );
                }, 0);

            return cbAmount;
        },
        percentSales: function() {
            const percent = parseFloat(
                (this.totalCBAmount * 100) / 
                    this.totalTransAmount
            ).toFixed(2);
            console.log('% of sales', percent);
            return percent > 0 ? percent.toString() : "0";
        },
        percentTransactions: function() {
            const percent = parseFloat(
                (this.totalCbs * 100) /
                    this.totalTrans
            ).toFixed(2);
            
            console.log('% of trnxs', percent);
            
            return percent > 0 ? percent : "0";
        },
        totalSales: function() {
            const sales = this.filteredData.reduce((prev, cur) => {
                return (parseFloat(prev) + parseFloat(cur.tran_amt)).toFixed(2);
            }, 0);
            // console.log(sales);
            return sales;
        },
        totalTrans: function() {
            const trans = this.filteredData.reduce((prev, cur) => {
                return prev + 1;
            }, 0);
            return trans;
        },
        totalTransAmount: function() {
            const trans = this.filteredData.reduce((prev, cur) => {
                return (parseFloat(prev) + parseFloat(cur.tran_amt)).toFixed(2);
            }, 0);
            return trans;
        },
        totalCbs: function() {
            const amount = this.filteredData.length;//reduce((prev, cur) => prev + cur.statuscode == 12? 1 : 0);
            return amount;
        },
        totalCBFees: function(){
          let res = 0;
          this.filteredData.forEach(x => {
            res += parseFloat(x.cb_fees);
          })
          return parseFloat(res).toFixed(2);
      }
    },
}
</script>

<style lang="scss">

@import "../assets/variables.scss";
@import "../assets/global.scss";

.lds-roller div:after{
  background: rgb(12, 12, 12) !important;
}
.merchant-row {
  .paper-box_head {
    padding-left: 30px;
    padding-right: 30px;
    border-color: #e1e7f0;
    .cb-sub-title {
      margin-bottom: 0;
    }
  }
}
.chart-row {
  .cb-filter-group {
    .date-time-picker {
      position: absolute !important;
      top: 0;
      bottom: 0;
      height: 100%;
      width: 100%;
      left: 0;
      > div {
        &:first-child {
          opacity: 0;
          height: 100%;
        }
      }
    }
    .cb-filter {
      position: relative;
    }
  }
}

</style>