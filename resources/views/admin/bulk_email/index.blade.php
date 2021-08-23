@extends('admin.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Sent bulk email
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{url('/send-new-bulk-email')}}" class="btn btn-danger btn-sm m-btn--air">Send new email</a>&nbsp;
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_bulk_email">
                <thead>
                    <tr>
                        <th>Sent on</th>
                        <th>To</th>
                        <th>Subject</th>
                        <th>Sent by</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recs as $rec)
                    <tr>
                        <td>{{$rec->sent_on}}</td>
                        <td>{!! $rec->to !!}</pre></td>
                        <td>{{$rec->subject}}</td>
                        <td>{{$rec->firstname." ".$rec->lastname}}</td>
                        <td>
                            <a href="{{url('/view-bulk-email/'.$rec->id)}}" class="btn btn-info btn-sm m-btn--air">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection