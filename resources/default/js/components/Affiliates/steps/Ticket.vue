<template>
    <div>
        <modal>
            <p class="lead mt-5 mb-5">We see you have purchased tickets to Xccelerate. Will you be attending?</p>

            <template v-slot:buttons>
                <button class="btn-custom btn-blue" @click="nextStep()">No</button>
                <button class="btn-custom btn-yellow" @click="willBeAttend()">Yes</button>
            </template>
        </modal>
    </div>
</template>

<script>
    import Modal from './../Modal';

    export default {
        name: "Ticket",
        components: {
            Modal
        },
        methods: {
            nextStep() {
                this.$eventHub.$emit('next-step');
            },
            willBeAttend() {
                this.ticketData.willBeAttending = true;

                this.nextStep();
            }
        },
        props: {
            ticketData: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>
    p {
        font-size: 25px;
        color: #fff;
    }
</style>
