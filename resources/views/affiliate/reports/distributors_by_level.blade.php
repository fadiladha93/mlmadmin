@extends('affiliate.layouts.main')

@section('main_content')
<div class="m-content">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head our_head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Organization
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body text-center">
                    <table class="table table-striped- table-bordered table-hover table-checkable">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>Distributors</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recs as $rec)
                            @if($rec->level != 0)
                            <tr>
                                <td>{{$rec->level}}</td>
                                <td>{{$rec->count}}</td>
                                <td>
                                    <button tag="{{url('/report/distributors_by_level_detail/'.$rec->level)}}" tag2="org-drill-down" class="btn btn-info btn-sm showDlg_l">Detail</button>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
