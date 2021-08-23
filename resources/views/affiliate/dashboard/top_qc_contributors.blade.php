@foreach($qcContributors as $k=>$c)
<div>
    <div class="m-widget1__item">
        <div class="row m-row--no-padding" style="align-items: center;">
            <div class="col-sm-5">
                <h3 class="m-widget1__title ri-qv-title">{{$c['firstname']}} {{$c['lastname']}}</h3>
            </div>
            <div class="col-sm-7 m--align-right ri-qv-sec">
                <div class="row top-producing">
                    <div class="col-md-12">
                        <span class="m-widget1__number m--font-{{$font[$k]}} ri-qv-value">{{number_format($c['total'], 2)}} / {{ number_format($limit) }}</span>
                        <span class="m-widget1__desc ri-qv-desc">QC</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>
@endforeach
