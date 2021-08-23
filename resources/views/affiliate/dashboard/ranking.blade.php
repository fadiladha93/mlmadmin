<div class="m-portlet m-portlet--mobile" id="divRanking">
    <div class="m-portlet__head our_head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Rank Insights
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body ri-wrapper">
        <div class="row">
            <div class="col-lg-4 rank ri-sec-1">
                <ul class="nav" id="summaryToggle">
                    <li class="nav-item">
                        <a class="nav-link active" id="volume-tab">Volume</a>
                    </li>
                    {{--TODO: Shall be available only for users with Current rank of ‘Executive Director’ --}}
                    @if($tsaRank)
                        <li class="nav-item">
                            <a class="nav-link" id="tsaCredits-tab">TSA Credits</a>
                        </li>
                    @endif
                </ul>
                <div class="row">
                    <div class="chart-row col-lg-7">
                        <h5 class="m-portlet__head-text chartHeader">
                            Lifetime Rank
                        </h5>
                        <span class="chart-value">{{$achieved_rank_desc}}</span>
                    </div>
                    <hr>
                    <div class="chart-row col-lg-12">
                        <h5 class="m-portlet__head-text chartHeader">
                            Previous Month / Paid-as Rank
                        </h5>
                        <span class="chart-value chart-value-danger">{{$prevRank}} / {{$paidRank}}</span>
                    </div>
                    <hr>
                    <div class="chart-row col-lg-12">
                        <h5 class="m-portlet__head-text chartHeader">
                            Current Month Rank
                        </h5>
                        <span class="chart-value chart-value-success">{{$monthly_rank_desc}}</span>
                    </div>
                    <hr>
                </div>

                <div class="row ri-summary-1">
                    <div class="col-md-12">
                        <div class="row summary-row active" id="volume">
                            <div class="col-lg-5 col-sm-6 summary-sec summary-cell">
                                <h4 class="m-portlet__head-text">
                                    Total Monthly QV
                                </h4>
                                <span class="chart-value chart-value-success chart-sum-value">{{ number_format($monthly_qv) }}</span>
                            </div>
                            <div class="col-lg-6 col-sm-6 summary-cell">
                                <h4 class="m-portlet__head-text">
                                    Rank Qualified Volume
                                </h4>
                                <span class="chart-value chart-value-success chart-sum-value" id="divQV">{{ number_format($qv) }}</span>
                            </div>
                        </div>
                        <div class="row summary-row" id="tsaCredits">
                            <div class="col-lg-5 col-sm-6 summary-sec summary-cell">
                                <h4 class="m-portlet__head-text">
                                    Active TSA Credits
                                </h4>
                                <span class="chart-value chart-value-success chart-sum-value">{{ number_format($activeQC, 2)}}</span>
                            </div>
                            <div class="col-lg-6 col-sm-6 summary-cell">
                                <h4 class="m-portlet__head-text">
                                    Qualifying TSA Credits
                                </h4>
                                <span class="chart-value chart-value-success chart-sum-value" id="divQC">{{ number_format($qualifyingQC, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="col-lg-4 rank ri-sec-2" style="position: relative;">
                <div class="chart-row qualifications-wrap">
                    {{--TODO: A user should not be able to select the Rank which less that their current Rank --}}
                    <select class="form-control m-input form-control-sm m-input--air m-input--pill rank-dp m-input--solid rank-type" id="cmbRankType">
                        @foreach($upper_ranks as $ur)
                        <option value="{{$ur->rankval}}">{{$ur->rankdesc}}</option>
                        @endforeach
                    </select>
                    <h4 class="m-portlet__head-text chartHeader">
                        Qualifications
                    </h4>
                    <span class="q-value" id="divQualification">{{strtoupper($rank_matric->nextlevel_rankdesc)}}</span>
                </div>
                <hr>
                <div class="row monthly-needed active">
                    <div class="col-md-6">
                        <div class="chart-row ri-summary-2">
                            <h4 class="m-portlet__head-text">
                                Monthly QV Needed
                            </h4>
                            <span class="chart-value chart-value-success chart-sum-value" id="divCurrentMonthlyQV">{{number_format($rank_matric->nextlevel_qv)}}</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="chart-row ri-summary-2">
                            <div class="chart-info">*No more than <span id="divPercentage">{{$rank_matric->nextlevel_percentage}}</span>% QV can come from a single personal leg.</div>
                        </div>
                    </div>
                </div>
                <div class="row monthly-needed">
                    <div class="col-md-6">
                        <div class="chart-row ri-summary-2">
                            <h4 class="m-portlet__head-text">
                                Monthly QC Needed
                            </h4>
                            <span class="chart-value chart-value-success chart-sum-value" id="divCurrentMonthlyQC">{{number_format($rank_matric->nextlevel_qc)}}</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="chart-row ri-summary-2">
                            <div class="chart-info">*No more than <span id="qcDivPercentage">{{$rank_matric->next_qc_percentage}}</span>% QC can come from a single personal leg.</div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row personally-enrolled
                @if($tsaRank)
                    active
                @endif
                        " style="position: relative;">
                    <div class="col-md-12">
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="chart-row ri-summary-2">
                                    <h4 class="m-portlet__head-text">
                                        Personally Enrolled Required
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="chart-row ri-summary-2 mb-2">
                                    <h4 class="m-portlet__head-text">
                                        Left
                                    </h4>
                                </div>
                                @if($binaryQualified['left'] >= $rank_matric->binary_limit)
                                    <div class="chart-value chart-value-success">
                                @else
                                    <div class="chart-value chart-value-danger">
                                @endif
                                        {{$binaryQualified['left']}}/<span class="binary-limit">{{$rank_matric->binary_limit}}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="chart-row ri-summary-2 mb-2">
                                    <h4 class="m-portlet__head-text">
                                        Right
                                    </h4>
                                </div>
                                @if($binaryQualified['right'] >= $rank_matric->binary_limit)
                                    <div class="chart-value chart-value-success">
                                @else
                                    <div class="chart-value chart-value-danger">
                                @endif
                                {{$binaryQualified['right']}}/<span class="binary-limit">{{$rank_matric->binary_limit}}</span>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 ri-sec-3">
                <h4 class="m-portlet__head-text chartHeader">
                    Top Producing Personal Legs
                </h4>
                <div>
                    <div class="m-widget1 ri-top-legs top-producing-block active" id="divTop3">
                        @include('affiliate.dashboard.top_contributors')
                    </div>
                    <div class="m-widget1 ri-top-legs top-producing-block" id="divTopQC">
                        @include('affiliate.dashboard.top_qc_contributors')
                    </div>
                </div>
            </div>
        </div>
                <div class="row subscriptions-types-wrap">
                    @if ($subscriptionTypes['standby']['count'] > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="subscription-type">
                                <h3 class="m-portlet__head-text chartHeader">
                                    {{ $subscriptionTypes['standby']['title'] }}
                                </h3>
                                <div class="details-btn">
                                    <a href="{{ $subscriptionTypes['standby']['url'] }}"
                                       target="_blank">Details</a>
                                </div>
                                <div class="amount m--font-danger">
                                    {{ $subscriptionTypes['standby']['count'] }}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($subscriptionTypes['tier-coach']['count'] > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="subscription-type">
                                <h3 class="m-portlet__head-text chartHeader">
                                    {{ $subscriptionTypes['tier-coach']['title'] }}
                                </h3>
                                <div class="details-btn">
                                    <a href="{{ $subscriptionTypes['tier-coach']['url'] }}"
                                       target="_blank">Details</a>
                                </div>
                                <div class="amount m--font-success">
                                    {{ $subscriptionTypes['tier-coach']['count'] }}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($subscriptionTypes['traverus-gf']['count'] > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="subscription-type">
                                <h3 class="m-portlet__head-text chartHeader">
                                    {{ $subscriptionTypes['traverus-gf']['title'] }}
                                </h3>
                                <div class="details-btn">
                                    <a href="{{ $subscriptionTypes['traverus-gf']['url'] }}"
                                       target="_blank">Details</a>
                                </div>
                                <div class="amount m--font-warning">
                                    {{ $subscriptionTypes['traverus-gf']['count'] }}
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($subscriptionTypes['standard']['count'] > 0)
                        <div class="col-lg-3 col-md-6">
                            <div class="subscription-type">
                                <h3 class="m-portlet__head-text chartHeader">
                                    {{ $subscriptionTypes['standard']['title'] }}
                                </h3>
                                <div class="details-btn">
                                    <a href="{{ $subscriptionTypes['standard']['url'] }}"
                                       target="_blank">Details</a>
                                </div>
                                <div class="amount m--font-info">
                                    {{ $subscriptionTypes['standard']['count'] }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="rankFootnote">* All Rank qualifications are based on Qualifying Volume only</div>
    </div>
</div>
