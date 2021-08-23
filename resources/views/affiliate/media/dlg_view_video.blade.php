<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{$rec->display_name}}</h5>
            @if($rec->is_external == 0 && $rec->is_downloadable == 1)
            <div class="pull-right"><a class="btn btn-danger btn-sm m-btn--air" href="{{url('/download-media/'.$rec->file_name)}}">Download</a></div>
            @endif
        </div>
        <div class="modal-body">
            @if($rec->is_external == 0)
            <video src="{{asset('/media_files/'.$rec->file_name)}}" autoplay controls style="width:100%"></video>
            @else
            <video src="{{$rec->external_url}}" autoplay controls style="width:100%"></video>
            @endif
        </div>
    </div>
</div>
