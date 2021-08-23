<template>
    <div class="row">
        <div class="col-md-12 types-buttons-wrap">
            <a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'calculate'}" href="/commission-control-center/calculate">Calculate</a>
            <!--<a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'adjustment'}" href="/commission-control-center/adjustment">Adjustment</a>-->
            <!--<a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'audit'}" href="/commission-control-center/audit">Audit</a>-->
            <a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'posting'}" href="/commission-control-center/posting">Posting</a>
            <a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'payout'}" href="/commission-control-center/payout">Payout</a>
            <a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'progress'}" href="/commission-control-center/progress">Commission Progress</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ModificationTypesButtons',
        props: {
            type: String
        },
    }
</script>
