<input type="hidden" value="{{$d_rank}}" id="d_rank">
<div class="modal-dialog modal-lg" style="max-width: 90%;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="">Lifetime Rank Report - ({{$d_rank}})</h5>
            <input name="rank" value="{{$d_rank}}" type="hidden" id="d_rank" />
            <button class="btn btn-info btn-sm m-btn--air" id="exp_distributors_by_rank_detail" style="margin-left: auto;">Export</button>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: unset;">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="content">
                <input id="distlevel" value="" type="hidden">

                <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_distributors_by_rank_detail">
                    <thead>
                        <tr>
                            <th>Dist ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>