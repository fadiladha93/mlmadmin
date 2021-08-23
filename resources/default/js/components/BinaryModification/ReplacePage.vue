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
                <div class="col-md-6">
                    <div class="alert alert-success">{{ successMessage }}</div>
                </div>
            </div>
            <div class="row" v-if="errorMessage">
                <div class="col-md-6">
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
                    <span>Replace with TSA#</span>
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
                    <div>Replace with TSA #:</div>
                    <div class="info-value">{{parentTSA}}</div>
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
        name: 'ReplacePage',
        components: {
            ModificationTypesButtons,
            ConfirmModal,
            BinaryTable,
            BallLoader,
        },
        data() {
            return {
                modificationType: 'replace',
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
                    url: baseUrl + '/binary-modification/replace/execute',
                    data: JSON.stringify({
                        fromTsa: self.parentTSA,
                        toTsa: self.distributorTSA,
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

                        self.showToastr('The Agent has been successfully replaced on the binary tree.', 'success');
                    },
                    error: function (result) {
                        self.showToastr(result.responseJSON.error, 'danger');
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
                    url: baseUrl + '/binary-modification/replace/parent',
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
                    url: baseUrl + '/binary-modification/replace/agent',
                    data: JSON.stringify({
                        agentTsa: newParentTsa,
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
            showToastr(message, type) {
                $.notify(message, {
                    type: type,
                    allow_dismiss: true,
                    delay: 15000,
                    newest_on_top: true,
                    z_index: 999999,
                    placement: {
                        from: 'top',
                        align: 'center'
                    },
                    animate: {
                        enter: 'animated bounce',
                        exit: 'animated bounce'
                    }
                });
            }
        }
    }
</script>
