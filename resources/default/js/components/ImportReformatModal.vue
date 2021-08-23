<template>
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Import incomplete - Map Headers
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table">
                <thead>
                <tr>
                    <th>Header</th>,
                    <th>Mapping</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="expectedHeader in expectedHeaders" :key="expectedHeader">
                    <td>{{ expectedHeader }}</td>
                    <td>
                        <select class="bootstrap-select">
                            <option value="-1" disabled selected>-- Select a header --</option>
                            <option v-for="actualHeader in actualHeaders" :value="actualHeader">{{ actualHeader }}</option>
                            <option value="-1">-- Not Present --</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ImportReformatModal',
        props: [
          'expectedHeaders',
          'actualHeaders'
        ],
        components: { },
        data() {
            return {
                errorMessage: null,
                successMessage: null
            }
        },
        methods: {

        }
    }
</script>
