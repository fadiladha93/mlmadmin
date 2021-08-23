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
            <div class="row" style="margin-top: 30px;">
                <div class="col-6">
                    <h5>Team / Group Import</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-6 mt-2 font-weight-normal">
                    The import feature is used to migrate entire teams, organizations, or groups, and must be set up correctly in the original CSV file. All fields are required to assure proper import of the group. Ensure all information is correct before importing.
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
            <div class="row mt-4">
                <div class="col-12 upload-file-wrap">
                    <form class="upload-file-wrap" method="post" enctype="multipart/form-data">
                        <div class="custom-file mr-3">
                            <input type="file" accept=".csv" class="custom-file-input upload-input" id="customFileLang">
                            <label class="custom-file-label" for="customFileLang">Select File</label>
                        </div>
                        <div>
                            <BallLoader v-if="isLoading" />
                            <button v-else type="button" class="btn btn-info btn-sm" @click="onImportBtnClick" style="min-width: 88px; height: 40px;">Import</button>
                        </div>
                    </form>
                    <div id="upload-file-error" class="error-messages">The file could not be uploaded. Only files with the following extensions are allowed: .csv.</div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col font-weight-normal">
                    <span>Imported Data</span>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <BinaryTable v-bind:distributors="distributors"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ModificationTypesButtons from './partials/ModificationTypesButtons'
    import BinaryTable from './partials/BinaryTable'
    import BallLoader from './../partials/BallLoader'

    export default {
        name: 'ImportPage',
        components: {
            ModificationTypesButtons,
            BinaryTable,
            BallLoader,
        },
        data() {
            return {
                modificationType: 'import',
                parentTSA: '',
                agentErrorMessage: null,
                parentErrorMessage: null,
                isLoading: false,
                isValidationError: false,
                distributors: [],
                newParent: [],
                successMessage: null,
                errorMessage: null,
            }
        },
        methods: {
            onImportBtnClick() {
                this.isValidationError = false;

                if (this.isLoading) {
                    return;
                }

                if (!this.parentTSA) {
                    this.parentErrorMessage = 'Is required';
                    this.isValidationError = true;
                }

                if (!this.isValidationError) {
                    this.importData();
                }
            },
            importData() {
                const self = this;
                self.setHeaders();
                self.isLoading = true;

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/binary-modification/import/insert',
                    data: JSON.stringify({
                        parentTsa: self.parentTSA,
                    }),
                    cache: false,
                    dataType: 'json',
                    success: function (result) {
                        self.distributors = [];
                        self.newParent = [];
                        self.parentTSA = '';
                        $('#newParentTsaNumber')[0].value = '';
                        // TODO: show notification after success insert process

                        self.setDistributorsData(result.distributors);

                        self.successMessage = 'Distributors have been successfully imported to the binary tree.';
                    },
                    error: function (result) {
                        self.errorMessage = result.responseJSON.error;
                    },
                    complete: function () {
                        self.isLoading = false;
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
                    url: baseUrl + '/binary-modification/import/parent',
                    data: JSON.stringify({
                        parentTsa: newParentTsa,
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
            setParentData(agent) {
                const rowData = this.mapUserToTable(agent);
                this.newParent = [rowData];
                this.parentTSA = rowData.tsanumber;
            },
            setDistributorsData(distributors) {
                const distributorsArray = [];
                distributors.map((distributor) => {
                    const rowData = this.mapUserToTable(distributor);
                    distributorsArray.push(rowData);
                });
                this.distributors = distributorsArray;
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
