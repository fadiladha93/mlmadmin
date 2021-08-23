<div class="m-widget4">
    @foreach($recs as $rec)
    <div class="m-widget4__item">
        <div class="m-widget4__img m-widget4__img--icon">
            <img src="{{asset('/assets/images/media_file_ext/doc.svg')}}" alt="">
        </div>
        <div class="m-widget4__info">
            <span class="m-widget4__text">
                {{$rec->display_name}}
            </span>
        </div>
        <div class="m-widget4__ext">
            @if($rec->is_external == 0)
            <a href="{{asset('/media_files/'.$rec->file_name)}}" target="_blank" class="m-widget4__icon">
                <i class="la la-download"></i>
            </a>
            @else
            <a href="{{$rec->external_url}}" target="_blank" class="m-widget4__icon">
                <i class="la la-download"></i>
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

