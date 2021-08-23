<template>
    <div class="binary-table-wrap">
        <table class="table binary-table">
            <thead class="thead-light">
            <tr>
                <th>Order Number</th>
                <th>Order Date</th>
                <th>TSA Associated</th>
                <th>Dist ID/Username</th>
                <th>Amount</th>
                <th>CV</th>
                <th>QV</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="data.length" class="binary-user-row item" v-for="item in data">
                <td><div>{{item.ordernumber}}</div></td>
                <td><div>{{item.orderdate}}</div></td>
                <td><div>{{item.tsaassociated}}</div></td>
                <td><div>{{item.username}}</div></td>
                <td><div>${{item.amount}}</div></td>
                <td><div>{{item.cv}}</div></td>
                <td><div>${{item.qv}}</div></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'DualTeamOrdersTable',
        props: {
            data: Array
        },
    }
</script>
