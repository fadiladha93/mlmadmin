<template>
    <div class="ui table-content-row">
        <div class="paper-box">
            <div class="paper-box_head">
                <h3 class="cb-sub-title">Current Imports</h3>
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="importsSearch"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                        class="p-0 mx-2"
                    ></v-text-field>
            </div>
            <hr class="mt-0" />
            <div class="paper-box_content">
                <v-data-table    
                    v-model="selectedImports"
                    :headers="importsHeaders"
                    item-key="id"
                    :items="imports"
                    :items-per-page="10"
                    :single-select="false"
                    show-select
                    :search="importsSearch"
                    :loading="loadingImports" loading-text="Loading... Please wait"

                    class="elevation-1"
                >
                </v-data-table>
            </div>
        </div>
        <hr class="my-1" />
        <div class="paper-box">
            <div class="paper-box_head">
                <h3 class="cb-sub-title">Imports Data</h3>
                    <v-spacer></v-spacer>
                    <v-btn depressed color="accent" @click="processChargebacks">Process Chargeback</v-btn>
                    <v-text-field
                        v-model="importsDataSearch"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                        class="p-0 mx-2"
                    ></v-text-field>
            </div>
            <hr class="mt-0" />
            <div class="paper-box_content">
                <v-data-table    
                    v-model="selectedImportsData"
                    :headers="importsDataHeaders"
                    item-key="id"
                    :items="importsData"
                    :items-per-page="10"
                    :single-select="false"
                    show-select
                    :search="importsDataSearch"
                    :loading="loadingImportsData" loading-text="Loading data... Please wait"
                    :item-class="getItemClass"
                    class="elevation-1"
                >
                </v-data-table>
            </div>
        </div>
    </div>
</template>

<script>
import * as axios from 'axios';
export default {
    data: () => {
        return {
            loadingImports: true,
            importsSearch: '',
            selectedImports:[],
            importsHeaders: [
                {
                    text: "Id",
                    align: "start",
                    sortable: false,
                    value: "id"
                },
                { text: "Merchant Id", value: "chargeback_merchant_id" },
                { text: "Created Date", value: "created_data" }
            ],
            imports: [
            ],

            loadingImportsData: true,
            importsDataSearch: '',
            selectedImportsData:[],
            importsDataHeaders: [
                {
                    text: "Transaction Id",
                    align: "start",
                    sortable: false,
                    value: "transaction_id"
                },
                { text: "Transaction Amount", value: "transaction_amount" },
                { text: "Chargeback Date", value: "chargeback_date" },
                { text: "Chargeback Amount", value: "chargeback_amount" },
                { text: "Chargeback Reason", value: "chargeback_reason_description" },
                { text: "Chargeback Subject", value: "chargeback_subject" },
                { text: "Chargeback Deadline Date", value: "chargeback_deadline_date" },
                { text: "Card Bin", value: "card_bin" },
                { text: "Card Last Four", value: "card_last_four" },
                { text: "Card Brand", value: "card_brand" },
                { text: "Card Holder", value: "card_holder" }
            ],
            importsData: [

            ],
        };
    },
    mounted: function() {
        const url = `/chargeback/imports`;
        return axios.get(url)
            // get data
            .then(x => {
                this.loadingImports = false;
                // this.imports = x.imports;
                this.imports = [
                    {
                        id: 1,
                        chargeback_merchant_id: 'NMI',
                        created_date: 10
                    },
                    {
                        id: 2,
                        chargeback_merchant_id: 'T1',
                        created_date: 13
                    },
                    {
                        id: 3,
                        chargeback_merchant_id: 'T2',
                        created_date: 0
                    },
                    {
                        id: 4,
                        chargeback_merchant_id: 'T3',
                        created_date: 8
                    }
                ];
            })
            .catch(err => {
                this.loadingImports = false;
                console.log(err);
            });
    },
    computed: {},
    methods: {
        processChargebacks(){

            let rowIds = this.selectedImportsData.map(x => x.id);
            axios.post('/chargeback/manage/process-chargebacks', rowIds)
                .then(x => {
                    
                })
                .catch(err => {
                    console.log(err);
                })
        },
        getItemClass(item){
            let result = '';
            if(this.isChargedback(item) && this.isRefunded(item)){
                result = "row-both";
            }
            else if(this.isChargedback(item)){
                result = "row-charged-back";
            }
            else if(this.isRefunded(item)){
                result = "row-refunded";
            }

            return result;
        },
        isChargedback(item){
            return item.status_code == 13
        },
        isRefunded(item){
            return item.status_code == 9 || item.status_code == 10 || item.status_code == 11;
        },
        getImportsData(){
            let importsIds = this.selectedImports.map(x => x.id);
            axios.get('/chargeback/import/data', importsIds)
                .then(x => {
                    this.loadingImportsData = false;
                    // this.importsData = x.data;
                    this.importsData = [
                        {
                            id: 0,
                            transaction_id: 1,
                            chargeback_date: '132',
                            chargeback_deadline_date: '321',
                            chargeback_amount: '34521',
                            chargeback_reason_description: 'ergreg',
                            chargeback_subject: 'gregehe',
                            transaction_amount: '12324',
                            card_bin: '251645',
                            card_last_four: '7894',
                            card_brand: 'gdgre',
                            card_holder: 'jhthrjr',
                            status_code: 10
                        },
                        {
                            id: 1,
                            transaction_id: 1,
                            chargeback_date: '132',
                            chargeback_deadline_date: '321',
                            chargeback_amount: '34521',
                            chargeback_reason_description: 'ergreg',
                            chargeback_subject: 'gregehe',
                            transaction_amount: '12324',
                            card_bin: '251645',
                            card_last_four: '7894',
                            card_brand: 'gdgre',
                            card_holder: 'jhthrjr',
                            status_code: 13
                        },
                        {
                            id: 2,
                            transaction_id: 1,
                            chargeback_date: '132',
                            chargeback_deadline_date: '321',
                            chargeback_amount: '34521',
                            chargeback_reason_description: 'ergreg',
                            chargeback_subject: 'gregehe',
                            transaction_amount: '12324',
                            card_bin: '251645',
                            card_last_four: '7894',
                            card_brand: 'gdgre',
                            card_holder: 'jhthrjr',
                            status_code: 5
                        },
                        {
                            id: 3,
                            transaction_id: 1,
                            chargeback_date: '132',
                            chargeback_deadline_date: '321',
                            chargeback_amount: '34521',
                            chargeback_reason_description: 'ergreg',
                            chargeback_subject: 'gregehe',
                            transaction_amount: '12324',
                            card_bin: '251645',
                            card_last_four: '7894',
                            card_brand: 'gdgre',
                            card_holder: 'jhthrjr',
                            status_code: 3
                        },
                    ];

                    this.importsData.forEach(item =>{
                        if(this.isChargedback(item) && this.isRefunded(item)){
                        }
                        else if(this.isChargedback(item)){
                            this.selectedImportsData.push(item);
                        }
                        else if(this.isRefunded(item)){
                            this.selectedImportsData.push(item);
                        }
                    })
                })
                .catch(err => {
                    this.loadingImportsData = false;
                    console.log(err);
                });
        }
    },
    watch: {
        selectedImports: function(){
            this.loadingImportsData = true;
            this.getImportsData();
        }
    }
};
</script>

<style lang="scss" >
@import "../../../../sass/_variables.scss";
@import "./assets/global.scss";

.row-charged-back{
    color: white;
    background-color: $red;
}
.row-charged-back:hover{
    color: white;
    background-color: $red !important;
}
.v-data-table__selected.row-charged-back{
    color: white;
    background-color: $red !important;
}
.row-refunded{
    color: white;
    background-color: $blue
}
.row-refunded:hover{
    color: white;
    background-color: $blue !important;
}
.v-data-table__selected.row-refunded{
    color: white;
    background-color: $blue  !important;
}

.v-data-footer{
    padding: 8px 8px !important;
}
</style>
