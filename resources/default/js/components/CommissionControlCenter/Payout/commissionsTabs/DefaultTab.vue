<template>
    <div v-if="totalData.length" class="row" style="margin-top: 25px;">
        <div class="col-12">
            <DefaultTotalTable v-bind:data="totalData"/>
        </div>
    </div>
</template>

<script>
    import DefaultTotalTable from '../../../partials/DefaultTotalTable'

    export default {
        name: 'DefaultTab',
        components: {
            DefaultTotalTable,
        },
        props: {
            totalData: Array,
        }
    }
</script>
