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
            <div class="row" style="margin-top: 20px;" v-if="showTerminateOptions">
                <div class="col-12 text-center">
                    <div class="radio-group multiple">
                        <div class="text">Which action would you like to perform with the distributor?</div>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline" v-if="!activeUser">
                                <input type="radio" class="custom-control-input" id="action-reactivate" v-model="agentAction" value="reactivate">
                                <label class="custom-control-label" for="action-reactivate">Reactivate</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline" v-if="activeUser">
                                <input type="radio" class="custom-control-input" id="action-inactivate" v-model="agentAction" value="inactivate">
                                <label class="custom-control-label" for="action-inactivate">Inactivate</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="action-delete" v-model="agentAction" value="delete">
                                <label class="custom-control-label" for="action-delete">Delete</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 5px;" v-show="optionErrorMessage && showTerminateOptions">
                <div class="col-12 text-center">
                    <span class="error-message-terminate">{{optionErrorMessage}}</span>
                </div>
            </div>
            <div class="row" style="margin-top: 35px;">
                <div class="col-12 text-center">
                    <BallLoader v-if="isLoading" />
                    <button v-else type="button" class="btn btn-info btn-sm" @click="onShowReviewModal" style="min-width: 88px;margin-bottom: 7px;">Submit</button>
                </div>
            </div>
            <ConfirmModal v-if="showModal"
                @close="showModal = false"
                @submit="onSubmitBtnClick">
                <div slot="message" class="message">
                    Please confirm your selection, this can not be undone.
                </div>
                <div slot="body" class="info-row">
                    <div>[{{ actionMessage }}] TSA #:</div>
                    <div class="info-value">{{distributorTSA}}</div>
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
        name: 'TerminatePage',
        components: {
            ModificationTypesButtons,
            ConfirmModal,
            BinaryTable,
            BallLoader,
        },
        data() {
            return {
                agentAction: '',
                activeUser: false,
                showTerminateOptions: false,
                modificationType: 'terminate',
                distributorTSA: '',
                showModal: false,
                agentErrorMessage: null,
                isLoading: false,
                isValidationError: false,
                distributors: [],
                successMessage: null,
                errorMessage: null,
                optionErrorMessage: null,
            }
        },
        watch: {
            agentAction: function () {
                this.optionErrorMessage = null;
            }
        },
        methods: {
            onShowReviewModal() {
                this.isValidationError = false;

                if (this.isLoading) {
                    return;
                }

                if (!this.distributorTSA) {
                    this.agentErrorMessage = 'Is required';
                    this.isValidationError = true;
                }

                if (!this.agentAction) {
                    this.optionErrorMessage = 'Please select the action';
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
                    url: baseUrl + '/binary-modification/terminate/execute',
                    data: JSON.stringify({
                        agentTsa: self.distributorTSA,
                        action: self.agentAction,
                    }),
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        var message = '';
                        switch(self.agentAction) {
                            case 'reactivate':
                                message = 'The distributor #' + self.distributorTSA + ' is reactivated';
                                break;
                            case 'inactivate':
                                message = 'The distributor #' + self.distributorTSA + ' is inactivated';
                                break;
                            case 'delete':
                                message = 'The distributor #' + self.distributorTSA + ' is deleted';
                                break;
                            default:
                        }

                        if (message) {
                            self.showToastr(message, 'success');
                        }

                        // clear data
                        self.distributors = [];
                        self.distributorTSA = '';
                        $('#agentTsaNumber')[0].value = '';

                        self.agentAction = '';
                        self.showTerminateOptions = false;
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
                    url: baseUrl + '/binary-modification/terminate/agent',
                    data: JSON.stringify({
                        nodeTsa: agentTsaNumber,
                    }),
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        self.agentErrorMessage = null;
                        self.setAgentData(result.data);
                        self.activeUser = self.distributors[0].active;
                        self.showTerminateOptions = true;
                        self.agentAction = '';
                    },
                    error: function (result) {
                        self.agentErrorMessage = result.responseJSON.error;
                        self.distributors = [];
                        self.distributorTSA = '';
                    }
                });
            },
            setAgentData(agent) {
                const rowData = this.mapUserToTable(agent);
                this.distributors = [rowData];
                this.distributorTSA = rowData.tsanumber;
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
                    active: data.active,
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
        },
        computed: {
            // a computed getter
            actionMessage: function () {
                var actionText = this.agentAction;
                return actionText.charAt(0).toUpperCase() + actionText.slice(1);
            }
        }
    }
</script>
