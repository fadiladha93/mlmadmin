<div class="m-portlet" style="margin-top:20px;">
    <div class="m-portlet__head our_head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Leads Snapshot
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <a class="btn m-btn--pill m-btn--air btn-success btn-sm showDlg_s" tag="{{url('/new-lead')}}" style="color:#FFF;"><i class="la la-plus"></i>
                <span>New Lead</span>
                </span></a>
        </div>
    </div>
    <div class="m-portlet__body" style="padding:10px;">
        <table class="table m-table m-table--head-bg-success">
            <thead>
                <tr>
                    <th>Lead name</th>
                    <th>Email</th>
                    <th>Phone number</th>
                    <th>Contact Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                <tr>
                    <td>{{$lead->name}}</td>
                    <td>{{$lead->email}}</td>
                    <td>{{$lead->phone}}</td>
                    <td>{{$lead->contact_date}}</td>
                    <td><span class="m-badge m-badge--{{App\Leads::getStatusColor($lead->status)}} m-badge--wide">{{$lead->status}}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-center">
            <a href="{{url('/leads')}}" class="btn m-btn--pill m-btn--air btn-success btn-sm" style="color:#FFF;">View All</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>