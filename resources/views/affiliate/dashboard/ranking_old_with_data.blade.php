<div class="m-portlet m-portlet--mobile">
    <div class="m-portlet__head our_head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Rank Insights
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="row">
            <div class="col-lg-4 rank">
                <h4 class="m-portlet__head-text chartHeader">
                    Current Rank
                </h4>
                <div>{{$rank->rankdesc}}</div>
                <div class="text-center">
                    <span class="v m--font-success">{{number_format($rank->rankqv)}}</span>
                    <span>Current Monthly QV</span>
                    <div>
                        <img src="{{asset('/assets/images/rank_placeholder2.png')}}"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 rank">
                <h4 class="m-portlet__head-text chartHeader">
                    Qualification
                </h4>
                <div>{{$rank->nextlevel_rankdesc}}</div>
                <div class="text-center">
                    <span class="v m--font-warning">{{number_format($rank->nextlevel_qv)}}</span>
                    <span>Monthly QV Needed</span>
                    <div>
                        <img src="{{asset('/assets/images/rank_placeholder.png')}}"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <h4 class="m-portlet__head-text chartHeader">
                    Top Producing Personal Legs
                </h4>
                {{--<div>No more than 50% QV can come<br/>from a single personal leg</div>--}}
                <div>
                    <div class="m-widget1">
                        @foreach($contributors as $k=>$c)
                            <div class="m-widget1__item">
                                <div class="row m-row--no-padding align-items-center">
                                    <div class="col">
                                        <h3 class="m-widget1__title">{{$c->firstname}} {{$c->lastname}}</h3>
                                    </div>
                                    <div class="col m--align-right">
                                        <span class="m-widget1__number m--font-{{$font[$k]}}">{{number_format($c->qv_contribution)}} / {{number_format($c->min_qv)}}</span>
                                        <span class="m-widget1__desc">QV</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
