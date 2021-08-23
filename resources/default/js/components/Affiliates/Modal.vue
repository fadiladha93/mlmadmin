<template>
    <div>
        <div class="backdrop"></div>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header cm-header">
                    <img alt="iBuumerang logo" src="/assets/images/logo.png" />
                </div>
                <div class="modal-body cm-body">
                    <div class="cm-body-inner">
                        <slot></slot>
                    </div>

                    <div class="action-buttons">
                        <slot name="buttons">
                            <button class="btn-custom btn-blue" v-show="this.currentStep > 0" @click="previousStep()">Back</button>
                            <button class="btn-custom btn-yellow" @click="nextStep()">Continue</button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Modal',
    data() {
        return {
            //
        }
    },
    computed: {
        currentStep: function () {
            return this.$parent.$parent.currentStep;
        },
    },
    methods: {
        nextStep() {
            this.$eventHub.$emit('next-step');
        },
        previousStep() {
            this.$eventHub.$emit('previous-step');
        }
    }
}
</script>

<style type="text/css">
    * {
        box-sizing: border-box;
    }

    p.lead {
        font-size: 20px;
        color: #fff;
        text-align: center;
    }

    .modal-dialog {
        position: absolute;
        left: 50%;
        z-index: 99999;
        width: 850px;
    }

    .modal-content {
        position: relative;
        right: 50%;
        border-style: none;
        border-radius: 0;
        //color: #fff;
    }

    .modal-content a.custom-anchor {
        color: #fff;
        border-bottom: 1px solid #fff;
        font-weight: bold;
    }

    .modal-header img {
        margin: 5px auto;
        width: 170px;
    }

    .backdrop {
        position: absolute;
        width: 100vw;
        height: 100vh;
        z-index: 99998;
        background: rgba(0, 0, 0, 0.5);
    }

    .cm-body {
        background: #4aafd1;
    }

    .cm-header {
        text-align: center;
        background: #fff;
        padding: 5px;
    }

    .cm-header img {
        max-width: 207px;
    }

    .cm-body-inner {
        padding: 10px;
        margin: auto;
    }

    span.required {
        color: red;
    }

    .action-buttons {
        text-align: center;
    }

    .btn-custom {
        line-height: 30px;
        border: 0;
        color: #fff;
        font-size: 16px;
        display: inline-block;
        border-radius: 10px;
        font-weight: 400;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all .5s ease;
        text-decoration: none;
        text-transform: uppercase;
        margin: 10px 0;
        padding: 5px 20px;
    }

    .btn-custom[disabled="disabled"] {
        background: red;
    }

    .btn-yellow {
        background: #f8bb00;
    }

    .btn-blue {
        background: #343a40;
    }

    .btn-yellow:hover {
        background: #222;
    }

    .btn-blue:hover {
        background: #222;
    }

    .form-group label {
        //color: #fff;
        text-transform: uppercase;
        font-weight: bold;
        font-size: 10px;
    }

    .form-group label span {
        color: red;
        font-weight: normal;
        font-size: 15px;
    }

    .form-group input:focus {
        border: none;
    }

    .form-group input[readonly], .form-group select[readonly] {
        background: #eeeeee;
    }

    button[disabled] {
        background: #eeeeee !important;
        color: #9699a2 !important;
    }

</style>
