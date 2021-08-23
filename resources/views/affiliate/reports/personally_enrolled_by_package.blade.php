<div class="modal-dialog modal-lg" style="max-width: 90%;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{$title}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="packId" value="{{$packId}}" />
            <div class="content">
                <input id="distlevel" value="" type="hidden">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_distributors_by_pack">
                    <thead>
                        <tr>
                            <th>Dist ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Username</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>