<template>
    <div class="binary-table-wrap">
        <table class="table binary-table">
            <thead class="thead-light double-rows">
                <tr>
                    <th>Name</th>
                    <th>TSA#</th>
                    <th>Total FSB</th>
                </tr>
            </thead>
            <tbody>
            <tr v-if="data.length === 0" class="binary-user-row item">
                <td><div></div></td>
                <td><div></div></td>
                <td><div></div></td>
            </tr>
            <tr v-else class="binary-user-row item" v-for="item in data">
                <td><div>{{item.name}}</div></td>
                <td><div>{{item.tsa}}</div></td>
                <td><div>${{item.totalFsb}}</div></td>
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
