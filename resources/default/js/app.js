/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');
import Search from './components/BinaryTree/Search.vue';
import ImportPage from './components/BinaryModification/ImportPage.vue';
import InsertPage from './components/BinaryModification/InsertPage.vue';
import MovePage from './components/BinaryModification/MovePage.vue';
import ReplacePage from './components/BinaryModification/ReplacePage.vue';
import TerminatePage from './components/BinaryModification/TerminatePage.vue';
import CalculatePage from './components/CommissionControlCenter/Calculate/CalculatePage.vue';
import AuditPage from './components/CommissionControlCenter/Audit/AuditPage.vue';
import AdjustmentPage from './components/CommissionControlCenter/Adjustment/AdjustmentPage.vue';
import PostingPage from './components/CommissionControlCenter/Posting/PostingPage.vue';
import PayoutPage from './components/CommissionControlCenter/Payout/PayoutPage.vue';
import Modal from './components/Affiliates/Modal.vue';
//Chargebacks
import ChargebackComponent from './components/merchants/Chargebacks/ChargebackComponent.vue'
import ChargebackSummary from './components/merchants/Chargebacks/ChargebackSummary.vue'
import ChargebackImport from './components/merchants/Chargebacks/ChargebackImport.vue'
import ChargebackManage from './components/merchants/Chargebacks/Manage/ChargebackManage.vue'
import StatsCard from './components/merchants/Chargebacks/Manage/StatsCard.vue'
import Overview from './components/merchants/Chargebacks/Manage/Overview.vue'
import MerchantHealth from './components/merchants/Chargebacks/Manage/MerchantHealth.vue'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker'
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';

import VuetableFieldSequence from 'vuetable-2/src/components/VuetableFieldSequence.vue'
import VuetableFieldCheckbox from 'vuetable-2/src/components/VuetableFieldCheckbox.vue'
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

    Vue.component('Search', require('./components/BinaryTree/Search.vue').default);
    Vue.component('import-page', require('./components/BinaryModification/ImportPage.vue').default);
    Vue.component('insert-page', require('./components/BinaryModification/InsertPage.vue').default);
    Vue.component('move-page', require('./components/BinaryModification/MovePage.vue').default);
    Vue.component('replace-page', require('./components/BinaryModification/ReplacePage.vue').default);
    Vue.component('terminate-page', require('./components/BinaryModification/TerminatePage.vue').default);
    Vue.component('calculate-page', require('./components/CommissionControlCenter/Calculate/CalculatePage.vue').default);
    Vue.component('audit-page', require('./components/CommissionControlCenter/Audit/AuditPage.vue').default);
    Vue.component('adjustment-page', require('./components/CommissionControlCenter/Adjustment/AdjustmentPage.vue').default);
    Vue.component('posting-page', require('./components/CommissionControlCenter/Posting/PostingPage.vue').default);
    Vue.component('payout-page', require('./components/CommissionControlCenter/Payout/PayoutPage.vue').default);
    Vue.component('progress-page', require('./components/CommissionControlCenter/Progress/ProgressPage.vue').default);
    Vue.component('steps', require('./components/Steps.vue').default);
    Vue.component('import-reformat-modal', require('./components/ImportReformatModal.vue').default);
    //Merchants
    Vue.component('chargeback', require('./components/merchants/Chargebacks/ChargebackComponent.vue').default);
    Vue.component('cb-summary', require('./components/merchants/Chargebacks/ChargebackSummary.vue').default);
    Vue.component('chargeback-import', require('./components/merchants/Chargebacks/ChargebackImport.vue').default);
    Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);
    Vue.component('chargeback-manage', require('./components/merchants/Chargebacks/Manage/ChargebackManage.vue').default);
    Vue.component('cb-stats-card', require('./components/merchants/Chargebacks/Manage/StatsCard.vue').default);
    Vue.component('cb-overview', require('./components/merchants/Chargebacks/Manage/Overview.vue').default);
    Vue.component('cb-merchant-health', require('./components/merchants/Chargebacks/Manage/MerchantHealth.vue').default);
    //Vuetable-2
    Vue.component('vuetable-field-checkbox', VuetableFieldCheckbox)
    /**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

if (document.getElementsByClassName("binary-tree-page").length) {
    new Vue({
        el: '.binary-tree-page',
        components: {
            Search
        }
    });
}

if (document.getElementsByClassName("binary-modification-page").length) {
    new Vue({
        el: '.binary-modification-page',
        components: {
            ImportPage,
            InsertPage,
            MovePage,
            ReplacePage,
            TerminatePage,
        }
    });
}

if (document.getElementsByClassName("commission-control-center-page").length) {
    new Vue({
        el: '.commission-control-center-page',
        components: {
            CalculatePage,
            AdjustmentPage,
            AuditPage,
            PostingPage,
            PayoutPage,
        }
    });
}

if (document.getElementsByClassName("modal-steps").length) {
    // Global Event Bus
    Vue.prototype.$eventHub = new Vue();

    new Vue({
        el: '.modal-steps',
        components: {
            Modal
        }
    });
}

if (document.getElementsByClassName("chargeback").length) {
    new Vue({
        el: '.chargeback',
        components: {
            ChargebackComponent,
            ChargebackImport
        }
    });
}

if (document.getElementsByClassName("chargeback-manage").length) {
    new Vue({
        el: '.chargeback-manage',
        components: {
            StatsCard,
            ChargebackManage,
            VueCtkDateTimePicker,
            Overview,
            MerchantHealth,
            ChargebackSummary
        }
    });
}
