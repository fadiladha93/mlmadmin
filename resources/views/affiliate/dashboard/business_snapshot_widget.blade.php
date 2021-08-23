<div class="m-portlet m-portlet--mobile" id="divBusinessSnapshot">
    <div class="m-portlet__head our_head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Business Snapshot
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body ri-wrapper">
        <div class="row" style="text-align: center">
            <div class="col-md col-sm-12">
                <h5>Qualifications</h5>
                <div class="row">
                    <div class="col-md col-sm-6 col-xs-6 b_content bc-xxs-6">
                        <div class="sub-head-div">
                            <h6>Active</h6>
                        </div>
                        <div class="bc_content">
                            <div>
                                @if($is_active == 1)
                                <img src="{{asset('/assets/images/active.png')}}" class="imgCross"/>
                                @else
                                <img src="{{asset('/assets/images/inactive.png')}}" class="imgCross"/>
                                @endif
                                <span class="bs_pv_v">PV {{$pv}}%</span>
                                <div class="bs_progress_c">
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{$pv}}%" aria-valuenow="{{$pv}}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <!--
                                <div style="margin-top:10px;">
                                    Activated* : Yes
                                </div>
                                -->
                            </div>
                        </div>
                        <div class="bs_desc">
                            <span>You need 100 PQV to be active<br/>*One-time 200PV Activation required</span>
                        </div>
                    </div>
                    <div class="col-md  col-sm-6 col-xs-6 b_content bc-xxs-6">
                        <div class="sub-head-div">
                            <h6>Binary Qualified</h6>
                        </div>
                        <div class="row bc_content" style="padding: 36px;">

                            <div class="col-md col-md-6 col-sm-6 col-xs-6 bc_bq_col">
                                <div class="bs_leg">
                                    <span>L</span>
                                    <div class="bs_placeholder_d">
                                        @if($binaryQualified['left'] > 0)
                                        <img src="{{asset('/assets/images/binary_active.png')}}" class="bs_placeholder"/>
                                        @else
                                        <img src="{{asset('/assets/images/binary_inactive.png')}}" class="bs_placeholder"/>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md col-md-6 col-sm-6 col-xs-6 bc_bq_col">
                                <div class="bs_leg">
                                    <span>R</span>
                                    <div class="bs_placeholder_d">
                                        @if($binaryQualified['right'] > 0)
                                        <img src="{{asset('/assets/images/binary_active.png')}}" class="bs_placeholder"/>
                                        @else
                                        <img src="{{asset('/assets/images/binary_inactive.png')}}" class="bs_placeholder"/>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bs_desc">
                            <span>Personally enrolled distributors that are active</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md  col-sm-12 bs_col">
                <h5>Volume</h5>
                <div class="row">
                    <div class="col-md col-sm-6 col-xs-6 b_content bc-xxs-6">
                        <div class="sub-head-div">
                            <h6>Binary Volume Current week</h6>
                        </div>
                        <div class="bc_content">
                            <div style="display: block;">
                                <div class="bs_binary_vol">
                                    <div><span class="bs_l">L</span> <span>{{number_format($total_left)}}</span></div>
                                </div>
                                <div class="bs_binary_vol">
                                    <span class="bs_l">R</span> <span>{{number_format($total_right)}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="bs_desc">
                            <span>Binary total volume</span>
                        </div>
                    </div>
                    <div class="col-md col-sm-6 col-xs-6 b_content bc-xxs-6">
                        <div class="sub-head-div">
                            <h6>Previous Month</h6>
                        </div>
                        <div class="bc_content">
                            <div class="bs_c">
                                <span>{{ number_format($prevQv) }}</span>
                            </div>
                        </div>
                        <div class="bs_desc">
                            <span>Rank Qualified Volume</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg col-sm-12 bs_col">
                <h5>Status & Rank</h5>
                <div class="row">
                    <div class="col-md col-sm-4 col-xs-4 b_content bc-xxs-4">
                        <div class="sub-head-div">
                            <h6>Enrollment Status</h6>
                        </div>
                        <div class="bc_content">
                            @if($currentProductId == App\Product::ID_NCREASE_ISBO)
                            <img src="{{asset('/assets/images/standby_class_2.png')}}"/>
                            @elseif($currentProductId == App\Product::ID_BASIC_PACK)
                            <img src="{{asset('/assets/images/coach_class.jpg')}}"/>
                            @elseif($currentProductId == App\Product::ID_VISIONARY_PACK || $currentProductId == App\Product::ID_Traverus_Grandfathering)
                            <img src="{{asset('/assets/images/business_class.jpg')}}"/>
                            @elseif($currentProductId == App\Product::ID_FIRST_CLASS || $currentProductId == App\Product::ID_EB_FIRST_CLASS)
                            <img src="{{asset('/assets/images/first_class.png')}}"/>
                            @elseif($currentProductId == App\Product::ID_PREMIUM_FIRST_CLASS)
                            <img src="{{asset('/assets/images/premium_fc.png')}}"/>
                            @elseif($currentProductId == App\Product::ID_VIBE_OVERDRIVE_USER)
                                <img src="{{asset('/assets/images/vibe_od_product.png')}}"/>
                            @endif
                        </div>
                        <div class="bs_desc">
                            <span>Selected Enrollment Pack</span>
                        </div>
                    </div>
                    <div class="col-md col-sm-4 col-xs-4 b_content bc-xxs-4">
                        <div class="sub-head-div">
                            <h6>Paid-As Rank</h6>
                        </div>
                        <div class="bc_content">
                            <div class="bs_c">
                                <span>{{ $paidRank }}</span>
                            </div>
                        </div>
                        <div class="bs_desc">
                            <span>Ranks as of the last 30 days</span>
                        </div>
                    </div>
                    <div class="col-md col-sm-4 col-xs-4 b_content bc-xxs-4">
                        <div class="sub-head-div">
                            <h6>Lifetime Rank</h6>
                        </div>
                        <div class="bc_content">
                            <div class="bs_c">
                                <span>{{$achieved_rank_desc}}</span>
                            </div>
                        </div>
                        <div class="bs_desc">
                            <span>Highest achieved rank</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
