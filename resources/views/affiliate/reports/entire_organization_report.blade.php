@extends('affiliate.layouts.main')

@section('content')
<main role="main" class="" style="width:100%; padding-left: 15px; padding-right: 15px;">

    <div class="starter-template">
        <div class="row" style="margin: 20px -15px 20px -15px">
            <div class="col-lg-12">
                <h3>Entire Organization Report</h3>
            </div>
        </div>
        <div id="afterHeaderDiv" style="float:right;">
        </div>
        <div id="ambassadorCounts">
            <div class="col-lg-8">
                <div class="row" style="margin: 20px -15px 20px -15px">
                    <div class="col-lg-4" id="recordsTotal">
                        <div class="row">
                            <h6>TOTAL AMBASSADORS</h6>
                        </div>
                        <div class="row">
                            <span></span>
                        </div>
                    </div>
                    <div class="col-lg-4" id="countActiveUsers">
                        <div class="row">
                            <h6>ACTIVE AMBASSADORS</h6>
                        </div>
                        <div class="row">
                            <span></span>
                        </div>
                    </div>
                    <div class="col-lg-4" id="totalLevels">
                        <div class="row">
                            <h6>TOTAL LEVELS</h6>
                        </div>
                        <div class="row">
                            <span>{{ $max_level }}</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                </div>
                <div class="col-lg-6">
                </div>
            </div>
        </div>
        <div class="m-form m-form__section--first m-form--label-align-right">
            <div class="form-group m-form__group row" id="controlsFormGroup" style="margin: 20px -15px 20px -15px">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-3" id="tableLengthDiv">
                        </div>
                        <div class="col-lg-3" id="labelSortDiv">
                            <label class="col-form-label m--pull-right">Level Sort</label>
                        </div>
                        <div class="col-lg-2">
                            <input type="number" class="form-control" min="0" max="{{ $max_level }}" placeholder="FROM LEVEL" id="levelFrom" value="{{ $levelFrom}}" />
                        </div>
                        <div class="col-lg-2">
                            <input type="number" class="form-control" min="0" max="{{ $max_level }}" placeholder="TO LEVEL" id="levelTo" value="{{ $levelTo }}" />
                        </div>
                        <div class="col-lg-2">
                            <button id="viewByLevel" class="btn btn-info m-btn--air btn-block">View</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 right" id="divForSearch">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-striped- table-bordered table-hover table-checkable display nowrap" id="dt_binary_tree_report" style="width:100%">
                    <thead>
                        <tr>
                            <th>Dist ID</th>
                            <th>Level</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Enrollment Date</th>
                            <th>Country</th>
                            <th>State/Province</th>
                            <th>Pack</th>
                            <th>Sponsor ID</th>
                            <th>Sponsor Name</th>
                            <th>Lifetime Rank</th>
                            <th>Paid-As Rank</th>
                            <th>Active Status</th>
                            <th>Binary Qualified</th>
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


