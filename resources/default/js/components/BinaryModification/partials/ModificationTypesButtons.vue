<template>
    <div class="row">
        <div class="col-md-12 types-buttons-wrap">
            <a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'insert'}" href="/binary-modification/insert">Insert</a>
            <a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'move'}" href="/binary-modification/move">Move</a>
            <a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'replace'}" href="/binary-modification/replace">Replace</a>
            <a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'terminate'}" href="/binary-modification/terminate">Terminate</a>
            <!--<a class="btn btn-secondary btn-sm" v-bind:class="{'active': type === 'import'}" href="#">Import</a>-->
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
