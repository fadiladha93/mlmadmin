<template>
    <div>
        <!-- Person Info -->
        <personal-user-info v-if="getCurrentComponent() === 'personal-user-info'" :user-info="stepsData.personalUserData"></personal-user-info>

        <!-- Address -->
        <address-user v-if="getCurrentComponent() === 'address-user'" :address-data="stepsData.addressUser"></address-user>

        <!-- CC/Tax -->
        <!-- <cc-tax v-if="getCurrentComponent() === 'cc-tax'" :cc-data="stepsData.ccTax"></cc-tax> -->

        <!-- Ticket -->
        <ticket v-if="getCurrentComponent() === 'ticket'" :ticket-data="stepsData.ticket"></ticket>

        <!-- Thanks -->
        <thanks v-if="getCurrentComponent() === 'thanks'"></thanks>
    </div>
</template>

<script>
    import axios from 'axios';
    import PersonalUserInfo from './Affiliates/steps/PersonalUserInfo';
    import AddressUser from './Affiliates/steps/AddressUser';
    import CcTax from './Affiliates/steps/CcTax';
    import Ticket from './Affiliates/steps/Ticket';
    import Thanks from './Affiliates/steps/Thanks';

    export default {
        name: 'Steps',
        data() {
            return {
                currentStep: 0,
                steps: [],
                stepsData: null
            }
        },
        components: {
            PersonalUserInfo,
            AddressUser,
            CcTax,
            Ticket,
            Thanks
        },
        methods: {
            next() {
                this.steps[this.currentStep].show = false;

                this.currentStep++;

                this.steps[this.currentStep].show = true;
            },
            previous() {
                this.steps[this.currentStep].show = false;

                this.currentStep--;

                this.steps[this.currentStep].show = true;
            },
            finish() {
                axios({
                    method: 'POST',
                    url: '/saves-user-data',
                    data: {
                        'userData': this.stepsData
                    },
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(res => {
                    this.steps[this.currentStep].show = false;

                    this.currentStep = -1;
                }).catch(err => {
                    console.log(err);
                });
            },
            getUserData() {
                axios({
                    url: '/checks-user-data'
                }).then(res => {
                    this.stepsData = res.data;

                    // If the object exists, push the component to the steps list
                    if (typeof res.data.personalUserData !== 'undefined') {
                        this.steps.push({ component: 'personal-user-info', show: false });
                    }

                    // If the object exists, push the component to the steps list
                    if (typeof res.data.addressUser !== 'undefined') {
                        this.steps.push({ component: 'address-user', show: false });
                    }

                    // If the object exists, push the component to the steps list
                    // if (typeof res.data.ccTax !== 'undefined') {
                    //     this.steps.push({ component: 'cc-tax', show: false });
                    // }

                    // If the object exists, push the component to the steps list
                    if (res.data.ticket.hasTicket === true) {
                        this.steps.push({ component: 'ticket', show: false });
                    }

                    // If the steps lists contains at least one component, push the thanks compo nent to the end
                    if (this.steps.length > 0) {
                        this.steps.push({ component: 'thanks', show: false });
                    }

                    // Sets the first step in the steps list as current and shows on screen
                    this.setCurrentComponent();
                }).catch(err => {
                    //
                });
            },
            getCurrentComponent() {
                return this.steps.filter(step => step.show === true).map(step => step.component)[0];
            },
            setCurrentComponent() {
                return this.steps[this.currentStep].show = true;
            }
        },
        mounted() {
            this.getUserData();
        },
        created() {
            this.$eventHub.$on('next-step', this.next);
            this.$eventHub.$on('previous-step', this.previous);
            this.$eventHub.$on('finish', this.finish);
        },
        beforeDestroy() {
            this.$eventHub.$off('next-step');
            this.$eventHub.$off('previous-step');
            this.$eventHub.$off('finish');
        }
    }
</script>
