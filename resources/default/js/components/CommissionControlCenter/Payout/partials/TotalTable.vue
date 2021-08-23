<template>
    <div class="binary-table-wrap">
        <table class="table binary-table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Start Date</th>
                    <th scope="col">Close Date</th>
                    <th scope="col">#TSA paid</th>
                    <th scope="col">Average Check</th>
                    <th scope="col">Highest Check</th>
                    <th scope="col">Highest Check TSA#</th>
                    <th scope="col">Total Commission</th>
                </tr>
            </thead>
            <tbody>
                <tr class="binary-user-row item" v-for="item in data">
                    <td><div>{{item.startdate}}</div></td>
                    <td><div>{{item.closedate}}</div></td>
                    <td><div>{{item.tsapaid}}</div></td>
                    <td><div>${{item.averagecheck}}</div></td>
                    <td><div>${{item.highestcheck}}</div></td>
                    <td><div>{{item.highestchecktsa}}</div></td>
                    <td><div>${{item.totalcommission}}</div></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'TotalTable',
        props: {
            data: Array
        },
    }
</script>
