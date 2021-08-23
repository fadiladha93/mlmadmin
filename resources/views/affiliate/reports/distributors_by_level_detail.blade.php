
@if(isset($level))
<input id="distlevel" value="{{$level}}" type="hidden">
<div class="modal-dialog modal-lg" style="max-width: 90%;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="">Level {{$level}} Enrollments - #{{$sponserid}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="content">
                <input id="distlevel" value="" type="hidden">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_distributors_by_level_detail">
                    <thead>
                        <tr>
                            <th>Dist ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Enrollment Pack</th>
                            <th>Sponsor ID</th>
                            {{--<th>Current Month Rank</th>--}}
                            {{--<th>Lifetime Rank</th>--}}
                            <th>Enrollment Date</th>
                            <th>Binary Leg</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@if(isset($distid))
<div class="modal-dialog modal-lg" style="max-width: 90%;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="">Enrollments (#{{$distid}} - {{$name}})</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="content">
                <input id="distid" value="{{$distid}}" type="hidden">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_distributors_by_level_detail">
                    <thead>
                        <tr>
                            <th>Dist ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Enrollment Pack</th>
                            <th>Sponsor ID</th>
                            {{--<th>Current Month Rank</th>--}}
                            {{--<th>Lifetime Rank</th>--}}
                            <th>Enrollment Date</th>
                            <th>Binary Leg</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endif


