<template>
    <div class="binary-table-wrap">
        <table class="table binary-table">
            <thead class="thead-light double-rows">
                <tr>
                    <th colspan="2">Carryover</th>
                    <th colspan="2">Current Pay Pd.</th>
                    <th colspan="2">Adjustment</th>
                    <th colspan="2">Total Volume</th>
                    <th colspan="2">Remainder</th>
                    <th rowspan="2">Paid %</th>
                    <th rowspan="2">Gross Volume</th>
                    <th rowspan="2">Total</th>
                </tr>
                <tr>
                    <th>Left</th>
                    <th>Right</th>
                    <th>Left</th>
                    <th>Right</th>
                    <th>Left</th>
                    <th>Right</th>
                    <th>Left</th>
                    <th>Right</th>
                    <th>Left</th>
                    <th>Right</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="data.length === 0" class="binary-user-row item">
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                </tr>
                <tr v-else class="binary-user-row item" v-for="item in data">
                    <td><div>{{item.carryoverleft}}</div></td>
                    <td><div>{{item.carryoverright}}</div></td>
                    <td><div>{{item.currentpayleft}}</div></td>
                    <td><div>${{item.currentpayright}}</div></td>
                    <td><div>${{item.adjustmentsleft}}</div></td>
                    <td><div>{{item.adjustmentsright}}</div></td>
                    <td><div>${{item.totalvolumeleft}}</div></td>
                    <td><div>${{item.totalvolumeright}}</div></td>
                    <td><div>${{item.remainderleft}}</div></td>
                    <td><div>${{item.remainderright}}</div></td>
                    <td><div>${{item.paid}}</div></td>
                    <td><div>${{item.grossvolume}}</div></td>
                    <td><div>${{item.total}}</div></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'DualTeamTotalTable',
        props: {
            data: Array
        },
    }
</script>
