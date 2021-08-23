<div>VIP users</div>

<table border="1">
    <tr>
        <td>Distributor Id</td>
        <td>First Name</td>
        <td>Last Name</td>
        <td>Email</td>
        <td>Username</td>
    </tr>
    @foreach($recs as $rec)
    <tr>
        <td>{{$rec->distid}}</td>
        <td>{{$rec->firstname}}</td>
        <td>{{$rec->lastname}}</td>
        <td>{{$rec->email}}</td>
        <td>{{$rec->username}}</td>
    </tr>
    @endforeach
</table>