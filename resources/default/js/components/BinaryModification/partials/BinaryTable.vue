<template>
    <div class="binary-table-wrap">
        <table class="table binary-table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">TSA Number</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Enrollment Date</th>
                    <th scope="col">Class</th>
                    <th scope="col">Lifetime Rank</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="distributors.length === 0" class="binary-user-row item">
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                    <td><div></div></td>
                </tr>
                <tr v-else class="binary-user-row item" v-for="distributor in distributors">
                    <td><div>{{distributor.tsanumber}}</div></td>
                    <td><div>{{distributor.firstname}}</div></td>
                    <td><div>{{distributor.lastname}}</div></td>
                    <td><div>{{distributor.username}}</div></td>
                    <td><div>{{distributor.enrollmentdate}}</div></td>
                    <td><div>{{distributor.class}}</div></td>
                    <td><div>{{distributor.rank}}</div></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'BinaryTable',
        props: {
            distributors: Array
        },
    }
</script>
