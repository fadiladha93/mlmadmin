<table class="table m-table m-table--head-separator-info">
    <thead>
        <tr>
            <th>Level</th>
            <th>Name</th>
            <th>Description</th>
            <th>Paid Percentage</th>
        </tr>
    </thead>
    <tbody>
    @foreach($commissions as $c)
        <tr>
            <td>{{$c->level}}</td>
            <td>{{ $c->user->firstname }} {{ $c->user->lastname }}</td>
            @if($type === \App\Http\Controllers\ReportController::UNILEVEL_KEY)
                <td>Level {{ $c->level }} Rank {{ \App\Services\UnilevelCommission::LEVEL_BY_RANK[$c->rank_id] }}</td>
            @else
                <td>Level {{ $c->level }} Rank {{ \App\Services\LeadershipCommission::LEVEL_BY_RANK[$c->rank_id] }}</td>
            @endif
            <td>{{$c->percent * 100}}%</td>
        </tr>
    @endforeach
    </tbody>
</table>
