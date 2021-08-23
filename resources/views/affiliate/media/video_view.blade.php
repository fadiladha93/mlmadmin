<div class="row">
    @foreach($recs as $rec)
    <div class="col-md-4" style="padding:5px;">
        @if($rec->is_external == 0)
        <video src="{{asset('/media_files/'.$rec->file_name)}}" tag="{{url('/view-video/'.$rec->id)}}" style="width:100%" class="showDlg_s"></video>
        @else
        <video src="{{$rec->external_url}}" tag="{{url('/view-video/'.$rec->id)}}" style="width:100%" class="showDlg_s"></video>
        @endif
        <div>
            <div class="pull-left">{{$rec->display_name}}</div>
            @if($rec->is_external == 0 && $rec->is_downloadable == 1)
            <div class="pull-right"><a href="{{url('/download-media/'.$rec->file_name)}}"><i class="la la-download" title="Download"></i></a></div>
            @endif
            <div class="clearfix"></div>
        </div>
    </div>
    @endforeach
</div>