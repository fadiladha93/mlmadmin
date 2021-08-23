<template>
    <div class="m-portlet m-portlet--mobile binary-modification-page">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Commission Control Center
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <ModificationTypesButtons v-bind:type="modificationType"/>

            <div class="binary-table-wrap status-info">
                <table class="table binary-table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Commission Type</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="binary-user-row item" v-for="job in jobs">
                            <td v-if="job.commission_type === 'LC'">
                                Leadership Commission (end of month - {{job.end_date}})
                            </td>
                            <td v-else-if="job.commission_type === 'UC'">
                                Unilevel Commission (end of month - {{job.end_date}})
                            </td>
                            <td v-else-if="job.commission_type === 'BC'">
                                Binary Commission (end of week - {{job.end_date}})
                            </td>
                            <td v-else>
                                FSB
                            </td>

                            <td>
                                {{ job.status }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import ModificationTypesButtons from '../partials/ModificationTypesButtons'

    export default {
        name: 'ProgressPage',
        components: {
            ModificationTypesButtons
        },
        props: {
            jobs: Array
        },
        data() {
            return {
                modificationType: 'progress'
            }
        },
        methods: {
        }
    }
</script>
