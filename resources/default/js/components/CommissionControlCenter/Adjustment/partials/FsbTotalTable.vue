<template>
    <div class="binary-table-wrap">
        <table class="table binary-table">
            <thead class="thead-light double-rows">
            <tr>
                <th>Name</th>
                <th>Sponsor Name</th>
                <th>Sponsor ID</th>
                <th>Total Volume</th>
                <th>FSB Persentage</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            <tr class="binary-user-row item" v-for="item in data">
                <td><div>{{item.name}}</div></td>
                <td><div>{{item.sponsorname}}</div></td>
                <td><div>{{item.sponsorid}}</div></td>
                <td><div>{{item.totalvolume}}</div></td>
                <td><div>{{item.fsbpersentage}}%</div></td>
                <td><div>${{item.total}}</div></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'FsbTotalTable',
        props: {
            data: Array
        },
    }
</script>
