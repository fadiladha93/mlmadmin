<template>
    <div>
        <div v-if="errors.length > 0">
            <br>
            <h4 class="col-md-4 offset-md-5">Issues</h4>
            <div class="form-group m-form__group row col-md-6 offset-md-4">
                <div class="form-group m-form__group row">
                    <ul>
                        <li v-for="error in errors" :key="error" class="form-text col-md-12 text-danger">{{error}}</li><br>
                    </ul>
                </div>
            </div>
        </div>
        <div v-if="this.currentImportStatus == this.STATUS_SUCCESS">
            <div class="alert alert-custom alert-primary fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">Successfully Imported for <b>{{ this.selectedMerchant.name }}</b> merchant</div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                    </button>
                </div>
            </div>
            
        </div>
        <div v-if="this.loader == true">          
            <div class="spinner"></div>
            <div class="alert alert-custom alert-warning" role="alert">
                <div class="alert-text">Please wait...</div>
            </div>            
        </div>
                <div v-if="this.validate == true">
            <div class="alert alert-custom alert-danger fade show" role="alert">
                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                <div class="alert-text">Ops! <b>{{ this.validateMsg }}</b></div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                    </button>
                </div>
            </div>
            
        </div>
        <form id="importSheetForm" method="post" enctype="multipart/form-data">
            <div class="m-form m-form__section--first m-form--label-align-right">
                <div class="form-group m-form__group row">
                    <label class="col-md-4 col-form-label">CSV File</label>
                    <div class="col-md-4" style="margin-top: 5px">
                        <input @change="loadCsv($event.target.files)" type="file" class="form-control form-control-sm form-control-file" accept=".csv" id="importFile" name="importFile" ref="importFile"
                                required>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label for="merchant_id" class="col-md-4 col-form-label">Merchant</label>
                    <div class="col-md-4" style="margin-top: 5px">
                        <select v-model="selectedMerchant" class="form-control form-control-sm" name="chargeback_merchant_id" id="chargeback_merchant_id" required>
                            <option disabled selected>-- Select a merchant --</option>
                            <option v-for="merchant in this.merchants" :key="merchant.id" :value="merchant.id">{{ merchant.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row justify-content-center mt-2">
                    <div class="col-md-4 offset-1">
                        <button @click="importCsv" type="button" id="btnImportSheet"
                                class="btn btn-info btn-sm m-btn--air"
                                :disabled="btnDisabled">Import
                        </button>
                        <div v-if="this.currentImportStatus == this.STATUS_SAVING" class="spinner spinner-primary mx-3"></div>
                        <button v-if="this.currentImportStatus == this.STATUS_SUCCESS" @click="toggleResults" type="button"
                                class="btn btn-success ml-2 btn-sm m-btn--air">{{this.resultsText}}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import * as axios from 'axios';
const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3, STATUS_MISSING_HEADERS = 4;

export default {
    props: {
        showSummary: {
            type: Boolean,
            required: true
        },
    },
    data: () => {
        return {
            STATUS_INITIAL: 0,
            STATUS_SAVING: 1,
            STATUS_SUCCESS: 2,
            STATUS_FAILED: 3,
            STATUS_MISSING_HEADERS: 4,
            importFile: {},
            merchants: [],
            uploadError: null,
            loader: false,
            validate: false,
            validateMsg: '',
            currentImportStatus: null,
            showMerchants: false,
            selectedMerchant: null,
            errors: [],
            btnDisabled: false
        }
    },
    methods: {
        toggleResults(state = null){
            this.$emit('toggle-results', state); 
        },
        reset() {
            // reset form to initial state
            this.currentImportStatus = STATUS_INITIAL;
            this.uploadError = null;

            const url = `/chargeback/merchants`;
            return axios.get(url)
                // get data
                .then((response)=>{
                    // console.log(response)
                    response.data.forEach((obj)=>{

                        this.merchants.push({id: obj.id, name: obj.name})
                        
                    })
                })
                .catch(err => {
                    console.log(err);
                });
        },
        loadCsv: function(files){
            // console.log(files)
            if(files && files.length > 0) this.importFile = files[0];
        },
        importCsv: function () {
            this.currentImportStatus = STATUS_SAVING;

            this.toggleResults(false);

            if(!this.$refs.importFile.files[0]){
               this.validate = true;
               this.validateMsg = 'CSV File is required.';
               return;
            }else if(this.selectedMerchant == null){
               this.validate = true;
               this.validateMsg = 'MERCHANT is required.';
               return;
            }


            const formData = new FormData();

            formData.append('importFile', this.importFile, this.importFile.name);
            formData.append('chargeback_merchant_id', this.selectedMerchant);
            this.loader = true;
            this.validate = false;
            this.btnDisabled = true;

            const url = `/chargeback/import`;
            return axios.post(url, formData)
                // get data
                .then(x => {
                    this.currentImportStatus = STATUS_SUCCESS;
                    this.loader = false;
                    this.btnDisabled = false;
                    if(x.errors)
                    {
                        this.errors = x.errors;
                    }
                    this.$emit('imported', {
                        status: this.currentImportStatus,
                        file: this.importFile,
                        activeFields: [
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
                            { text: "Card Holder", value: "card_holder" },
                            { text: "Card Holder", value: "card_holder" },
                            { text: "Card Holder", value: "card_holder" },
                            { text: "Card Holder", value: "card_holder" },
                            { text: "Card Holder", value: "card_holder" },
                            { text: "Card Holder", value: "card_holder" },
                            { text: "Card Holder", value: "card_holder" },
                        ]
                    })
                })
                .catch(err => {
                    console.log(err.response.data);
                    this.uploadError = err.response;
                    this.errors = [err.response.data];
                    this.currentImportStatus = STATUS_FAILED;
                    this.loader = false;
                    this.btnDisabled = false;
                });
        },
        selectMerchant: function(merchant){
            console.log('merchangr');
            
            this.selectedMerchant = merchant;
        },
    },
    computed: {
        resultsText(){
            return this.showSummary ? "Hide Results" : "Show Results";
        },
      isInitial() {
        return this.currentImportStatus === STATUS_INITIAL;
      },
      isSaving() {
        return this.currentImportStatus === STATUS_SAVING;
      },
      isSuccess() {
        return this.currentImportStatus === STATUS_SUCCESS;
      },
      isFailed() {
        return this.currentImportStatus === STATUS_FAILED;
      }
    },
    mounted() {
        this.reset();
    }
};
</script>

<style lang="scss" scoped>

</style>
