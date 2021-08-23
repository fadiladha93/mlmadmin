<input id="h_type" value="{{$type}}" type="hidden">
<input id="h_id" value="{{$id}}" type="hidden">
<div class="modal-dialog modal-lg" style="max-width: 90%;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="">{{$type}} #{{$id}} - Update History</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="content">
                <input id="distlevel" value="" type="hidden">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_dlg_update_history">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mode</th>
                            <th>Before Update</th>
                            <th>After Update</th>
                            <th>Created at</th>
                            <th>Updated By</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>