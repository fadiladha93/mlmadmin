<div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--skin-light  m-portlet--rounded-force" id="businss_widget">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text m--font-light">
                    Business Snapshot
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="nav nav-pills nav-pills--light m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link active" data-toggle="tab" role="tab" id="btnBS_thisMonth">
                        This Month
                    </a>
                </li>
                <li class="nav-item m-tabs__item">
                    <a class="nav-link m-tabs__link" data-toggle="tab" role="tab" id="btnBS_lastMonth">
                        Last Month
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <div class="m-widget17">
            <div class="m-widget17__visual m-widget17__visual--chart m-portlet-fit--top m-portlet-fit--sides m--bg-info">
                <div class="m-widget17__chart" style="padding-top: 0px;">
                    <canvas id="m_chart_activities"></canvas>
                </div>
            </div>
            <div class="m-widget17__stats" style="width:100%;">
                <div class="m-widget17__items m-widget17__items-col1">
                    <div class="m-widget17__item">
                        <span class="m-widget17__icon">
                        </span>
                        <span class="m-widget17__subtitle" id="divAcheivedRank">
                            {{$biz_acheived_rank}}
                        </span>
                        <span class="m-widget17__desc">
                            Achieved Rank
                        </span>
                    </div>
                    <div class="m-widget17__item">
                        <span class="m-widget17__icon">
                        </span>
                        <span class="m-widget17__subtitle" id="divMonthlyQV">
                            {{number_format($biz_monthly_qv)}}
                        </span>
                        <span class="m-widget17__desc">
                            Total Monthly QV
                        </span>
                    </div>
                </div>
                <div class="m-widget17__items m-widget17__items-col2">
                    <div class="m-widget17__item">
                        <span class="m-widget17__icon">
                        </span>
                        <span class="m-widget17__subtitle" id="divQulifiedVol">
                            {{number_format($biz_qulified_vol)}}
                        </span>
                        <span>
                            Rank Qualified Volume
                        </span>
                        <br>
                        <small>*Based on Leg Balance Requirements</small>
                    </div>
                    <div class="m-widget17__item">
                        <span class="m-widget17__icon">
                        </span>
                        <span class="m-widget17__subtitle" id="divComm">
                            {{$current_month_commission}}
                        </span>
                        <span class="m-widget17__desc">
                            Monthly Commission
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>