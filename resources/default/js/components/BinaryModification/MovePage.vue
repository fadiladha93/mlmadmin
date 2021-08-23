<template>
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Binary Modification
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row" v-if="successMessage">
                <div class="col-md-12">
                    <div class="alert alert-success">{{ successMessage }}</div>
                </div>
            </div>
            <div class="row" v-if="errorMessage">
                <div class="col-md-12">
                    <div class="alert alert-danger">{{ errorMessage }}</div>
                </div>
            </div>
            <ModificationTypesButtons v-bind:type="modificationType"/>
            <div class="row">
                <div class="col font-weight-normal input-wrap">
                    <span>TSA Number</span>
                    <div>
                        <input type="text" class="form-control" id="agentTsaNumber" v-on:keyup.enter="onAgentTsaSearch" />
                        <div class="error-messages" v-if="agentErrorMessage" style="display: block" >{{ agentErrorMessage }}</div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-12">
                    <BinaryTable v-bind:distributors="distributors"/>
                </div>
            </div>
            <div class="row">
                <div class="col font-weight-normal input-wrap">
                    <span>New Parent TSA#</span>
                    <div>
                        <input type="text" class="form-control" id="newParentTsaNumber" v-on:keyup.enter="onNewParentTsaSearch" />
                        <div class="error-messages" v-if="parentErrorMessage" style="display: block" >{{ parentErrorMessage }}</div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-12">
                    <BinaryTable v-bind:distributors="newParent"/>
                </div>
            </div>
            <div class="row" style="margin-top: 45px;">
                <div class="col-12">
                    <h5 style="border-bottom: 1px solid #eaebee; padding-bottom: 10px;">Placement Options</h5>
                </div>
            </div>
            <div class="row" style="margin-top: 7px;">
                <div class="col-4 text-center">
                    <div class="radio-group multiple">
                        <div class="text">Please specify which leg you move this position</div>
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
                <div class="col-4 text-center">
                    <div class="radio-group multiple">
                        <div class="text">Would you like to include the entire downline from this placement in the move?</div>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="includeDownline1" v-model="includeDownline" value="yes">
                                <label class="custom-control-label" for="includeDownline1">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="includeDownline2" v-model="includeDownline" value="no">
                                <label class="custom-control-label" for="includeDownline2">No</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <div class="radio-group multiple">
                        <div class="text">Would you like to mirror the downline for this placement? Outside leg = outside leg</div>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="mirrorDownline1" v-model="mirrorDownline" value="yes">
                                <label class="custom-control-label" for="mirrorDownline1">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="mirrorDownline2" v-model="mirrorDownline" value="no">
                                <label class="custom-control-label" for="mirrorDownline2">No</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 60px;">
                <div class="col-12 text-center">
                    <BallLoader v-if="isLoading" />
                    <button v-else type="button" class="btn btn-info btn-sm" @click="onShowReviewModal" style="min-width: 88px;margin-bottom: 7px;">Submit</button>
                </div>
            </div>
            <ConfirmModal v-if="showModal"
                @close="showModal = false"
                @submit="onSubmitBtnClick">
                <div slot="body" class="info-row">
                    <div>TSA #:</div>
                    <div class="info-value">{{distributorTSA}}</div>
                </div>
                <div slot="body" class="info-row">
                    <div>Parent TSA #:</div>
                    <div class="info-value">{{parentTSA}}</div>
                </div>
                <div slot="body" class="info-row">
                    <div>Direction:</div>
                    <div class="info-value">{{legDirection}}</div>
                </div>
                <div slot="body" class="info-row">
                    <div>Include Downline:</div>
                    <div class="info-value">{{includeDownline}}</div>
                </div>
                <div slot="body" class="info-row">
                    <div>Mirror Downline:</div>
                    <div class="info-value">{{mirrorDownline}}</div>
                </div>
            </ConfirmModal>
        </div>
    </div>
</template>

<script>
    import ModificationTypesButtons from './partials/ModificationTypesButtons'
    import ConfirmModal from './partials/ConfirmModal'
    import BinaryTable from './partials/BinaryTable'
    import BallLoader from './../partials/BallLoader'

    export default {
        name: 'MovePage',
        components: {
            ModificationTypesButtons,
            ConfirmModal,
            BinaryTable,
            BallLoader,
        },
        data() {
            return {
                modificationType: 'move',
                legDirection: 'right',
                includeDownline: 'yes',
                mirrorDownline: 'no',
                distributorTSA: '',
                parentTSA: '',
                showModal: false,
                agentErrorMessage: null,
                parentErrorMessage: null,
                newParent: [],
                isLoading: false,
                isValidationError: false,
                distributors: [],
                successMessage: null,
                errorMessage: null,
            }
        },
        methods: {
            onShowReviewModal() {
                this.isValidationError = false;

                if (this.isLoading) {
                    return;
                }

                if (!this.parentTSA) {
                    this.parentErrorMessage = 'Is required';
                    this.isValidationError = true;
                }

                if (!this.distributorTSA) {
                    this.agentErrorMessage = 'Is required';
                    this.isValidationError = true;
                }

                if (!this.legDirection) {
                    this.isValidationError = true;
                }

                if (!this.isValidationError) {
                    this.showModal = true;
                }
            },
            onSubmitBtnClick(er) {
                const self = this;
                self.setHeaders();
                self.showModal = false;
                self.isLoading = true;

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/binary-modification/move/execute',
                    data: JSON.stringify({
                        direction: self.legDirection,
                        fromTsa: self.distributorTSA,
                        toTsa: self.parentTSA,
                        isMirror: self.mirrorDownline === 'yes',
                        isDownline: self.includeDownline === 'yes',
                    }),
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        self.distributors = [];
                        self.newParent = [];
                        self.distributorTSA = '';
                        self.parentTSA = '';
                        $('#newParentTsaNumber')[0].value = '';
                        $('#agentTsaNumber')[0].value = '';
                        // TODO: show notification after success insert process

                        self.successMessage = 'The Agent has been successfully moved on the binary tree.';
                        self.errorMessage = '';
                    },
                    error: function (result) {
                        self.errorMessage = result.responseJSON.error;
                        self.successMessage = ''
                    },
                    complete: function () {
                        self.isLoading = false;
                    }
                });


            },
            onAgentTsaSearch: function() {
                const self = this;
                self.setHeaders();
                this.agentData = [];
                const agentTsaNumber = $('#agentTsaNumber')[0].value;

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/binary-modification/move/agent',
                    data: JSON.stringify({
                        nodeTsa: agentTsaNumber,
                    }),
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        self.agentErrorMessage = null;
                        self.setAgentData(result.data);
                    },
                    error: function (result) {
                        self.agentErrorMessage = result.responseJSON.error;
                        self.distributors = [];
                        self.distributorTSA = '';
                    }
                });
            },
            onNewParentTsaSearch: function() {
                const self = this;
                self.setHeaders();
                this.agentData = [];
                const newParentTsa = $('#newParentTsaNumber')[0].value;

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/binary-modification/move/agent',
                    data: JSON.stringify({
                        nodeTsa: newParentTsa,
                    }),
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        self.parentErrorMessage = null;
                        self.setParentData(result.data);
                    },
                    error: function (result) {
                        self.parentErrorMessage = result.responseJSON.error;
                        self.newParent = [];
                        self.parentTSA = '';
                    }
                });
            },
            setAgentData(agent) {
                const rowData = this.mapUserToTable(agent);
                this.distributors = [rowData];
                this.distributorTSA = rowData.tsanumber;
            },
            setParentData(agent) {
                const rowData = this.mapUserToTable(agent);
                this.newParent = [rowData];
                this.parentTSA = rowData.tsanumber;
            },
            mapUserToTable(data) {
                return {
                    firstname: data.firstname,
                    lastname: data.lastname,
                    username: data.username,
                    tsanumber: data.tsanumber,
                    enrollmentdate: data.enrollmentdate,
                    class: data.class,
                    rank: data.rank,
                }
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
