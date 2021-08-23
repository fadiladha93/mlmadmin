<div id="img_viewer"></div>
<div class="row" id="med_imgs">
    @foreach($recs as $rec)
    <div class="col-md-3" style="padding:5px;">
        @if($rec->is_external == 0)
        <img src="{{asset('/media_files/'.$rec->file_name)}}" alt="{{$rec->display_name}}" width="100%">
        @else
        <img src="{{$rec->external_url}}" alt="{{$rec->display_name}}" width="100%">
        @endif
        <div style="margin-top:5px;">
            <div class="pull-left">{{$rec->display_name}}</div>
            @if($rec->is_external == 0 && $rec->is_downloadable == 1)
            <div class="pull-right"><a href="{{url('/download-media/'.$rec->file_name)}}"><i class="la la-download" title="Download"></i></a></div>
            @endif
            <div class="clearfix"></div>
        </div>
    </div>
    @endforeach
</div>