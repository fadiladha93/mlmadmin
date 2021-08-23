<div class="m-portlet">
    <div class="m-portlet__body  m-portlet__body--no-padding">
        <div class="row m-row--no-padding m-row--col-separator-xl">
            <div class="col-md-3">
                <div class="wid_enroll showDlg_l" tag2="dist-by-pack" tag="{{url('/reports/personally-enrolled/1')}}" style="cursor:pointer;">
                    <h3 class="m-portlet__head-text chartHeader">
                        Standby
                    </h3>
                    <div class="c m--font-brand">
                        {{number_format($standby_count)}}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="wid_enroll showDlg_l" tag2="dist-by-pack" tag="{{url('/reports/personally-enrolled/2')}}" style="cursor:pointer;">
                    <h3 class="m-portlet__head-text chartHeader">
                        Coach Class
                    </h3>
                    <div class="c m--font-info">
                        {{number_format($coach_count)}}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="wid_enroll showDlg_l" tag2="dist-by-pack" tag="{{url('/reports/personally-enrolled/3')}}" style="cursor:pointer;">
                    <h3 class="m-portlet__head-text chartHeader">
                        Business Class
                    </h3>
                    <div class="c m--font-success">
                        {{number_format($business_count)}}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="wid_enroll showDlg_l" tag2="dist-by-pack" tag="{{url('/reports/personally-enrolled/4')}}" style="cursor:pointer;">
                    <h3 class="m-portlet__head-text chartHeader">
                        First Class
                    </h3>
                    <div class="c m--font-danger">
                        {{number_format($totalFirst)}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>