<div class="m-portlet m-portlet--fit" id="upgrades">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text chartHeader">
                    TraVerus - iDecide
                </h3>
            </div>
        </div>
    </div>
    <div class="m-portlet__body" style="padding:10px;">
        <div class="alert alert-danger text-center">
            @if($idecide_info == null)
            Monthly Upgrade<br/>$99.99/Month
            @else
            Default Password<br/>{{$idecide_info->password}}
            @endif
        </div>
        @if($idecide_info == null)
        <div>
            <button type="button" id="btnCreateTVIDecide" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--hover-info btn-block">Get iDecide Now !</button>
        </div>
        @endif
    </div>
</div>