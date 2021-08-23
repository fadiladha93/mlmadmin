<template>
    <div v-bind:class="getClassName(distributor)">
        <span class="number">{{ distributor.level - level }}</span>
        <a :href="'/binary-viewer/' + distributor.binary_id" class="btn m-btn distributor-btn">
            <div class="image ambassador"><UserIcon/></div>
            <span class="distributor-name">{{ distributor.firstname }} {{ distributor.lastname }}</span>
            <div class="image selected-pack"><PackIcon/></div>
        </a>
    </div>
</template>

<script>
    import UserIcon from './UserIcon'
    import PackIcon from './PackIcon'

    export default {
        props: {
            distributor: Object,
            packs: Object,
            ranks: Object,
            level: 0
        },
        components: {
            UserIcon,
            PackIcon
        },
        methods: {
            getActivityClass : function (distributor) {
                return distributor.current_product_id == 14 || (distributor.account_status !== 'SUSPENDED' && distributor.current_month_qv >= 100 && distributor.account_status !== 'TERMINATED')  ? 'active' : 'inactive';
            },

            getClassName : function (distributor) {
                return `${this.getActivityClass(distributor)} ${this.packs[distributor.current_product_id]} ${this.ranks[distributor.current_month_rank]}`;
            },
        }
    }
</script>
