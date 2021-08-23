<div class="m-portlet m-portlet--bordered-semi m-portlet--rounded-force">
    <div class="m-portlet__head m-portlet__head--fit">
        <div class="m-portlet__head-caption">

        </div>
    </div>
    <div class="m-portlet__body" style="padding: 0px 12px 10px;">
        <div class="m-widget19">
            <div class="m-widget19__pic m-portlet-fit--top m-portlet-fit--sides">
                <img src="{{asset('/promo/'.$promo->side_banner_img)}}" width="100%">
                <h4 class="m-widget19__title m--font-light">
                    {{$promo->side_banner_title}}
                </h4>
                <div class="m-widget19__shadow"></div>
            </div>
            <div class="m-widget19__content" style="margin-top:10px;">
                <div class="m-widget19__body">
                    {!!nl2br($promo->side_banner_short_desc)!!}
                </div>
            </div>
            <div class="m-widget19__action">
                <a href="{{url('/new-promo')}}" class="btn m-btn--pill btn-secondary m-btn m-btn--hover-info m-btn--custom">Read More</a>
            </div>
        </div>
    </div>
</div>