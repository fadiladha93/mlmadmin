@extends('affiliate.layouts.main')

@section('content')
    <main role="main" class="" style="width:100%; padding-left: 15px; padding-right: 15px;">
        <div class="starter-template">
            <div class="row" style="margin: 20px -15px 20px -15px">
                <div class="col-lg-12">
                    <h3>Weekly Enrollment Report</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped- table-bordered table-hover table-checkable display nowrap"
                           id="dt_weekly_enrollment" style="width:100%">
                        <thead>
                        <tr>
                            <th>Dist ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Country</th>
                            <th>State/Province</th>
                            <th>Pack</th>
                            <th>Sponsor ID</th>
                            <th>Sponsor Name</th>
                            <th>Enrollment Date</th>
                        </tr>
                        </thead>
                </div>
            </div>
            </table>
        </div>

    </main><!-- /.container -->

    <script>
        var csrfToken = '{{ csrf_token() }}';
        var baseUrl = '{{url(' / ')}}';
    </script>
@endsection

@section('scripts')
    <script src="{{asset('/js/binary.viewer.js')}}" type="text/javascript"></script>
@endsection


