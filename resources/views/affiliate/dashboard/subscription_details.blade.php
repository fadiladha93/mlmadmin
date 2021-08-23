@extends('affiliate.layouts.main')

@section('content')
    <main role="main" class="" style="width:100%; padding-left: 15px; padding-right: 15px;">

        <div class="starter-template">
            <div class="row" style="margin: 20px -15px 20px -15px">
                <div class="col-lg-12">
                    <h3>{{ $title }} User Details</h3>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-12">
                    @if ($subscriptionType == \App\Services\SubscriptionGroupService::TRAVERUSGF__ALIAS)
                        <table class="table table-striped- table-bordered table-hover table-checkable display nowrap" id="subscription-details-traverus-table" style="width:100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>TSA #</th>
                                <th>Username</th>
                                <th>Traverus Grandfathering Enrollment Date</th>
                                <th>Monthly Membership (Traverus) Enrollment Date</th>
                            </tr>
                            </thead>
                        </table>
                    @else
                        <table class="table table-striped- table-bordered table-hover table-checkable display nowrap" id="subscription-details-table" style="width:100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>TSA #</th>
                                <th>Username</th>
                                <th>Enrollment Date</th>
                            </tr>
                            </thead>
                        </table>
                    @endif

                </div>
            </div>
        </div>

        <input type="hidden" id="sponsor_id" value="{{ $user->id }}">
        <input type="hidden" id="subscription_type" value="{{ $subscriptionType }}">

    </main><!-- /.container -->

    <script>
        var csrfToken = '{{ csrf_token() }}';
        var baseUrl = '{{url(' / ')}}';
    </script>
@endsection

@section('scripts')
    <script src="{{asset('/js/binary.viewer.js')}}" type="text/javascript"></script>
@endsection


