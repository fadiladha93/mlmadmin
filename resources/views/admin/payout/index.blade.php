@extends('admin.layouts.main')

@section('main_content')
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            iPayout Control
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools" id="frmPayoutSetDefault">
                        <select classpayout-control="form-control form-control-sm" name="payout_method">
                            <option value="" selected=""></option>
                            <option value="iPayout">iPayout</option>
                            <option value="payquicker">PayQuicker</option>
                        </select>
                    &nbsp;
                        <a class="btn btn-info btn-sm m-btn--air" id="payoutSetDefault">Set As Default</a>
                    </form>
                </div>
            </div>
            <div class="m-portlet__body">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="dt_payout_control">
                    <thead>
                    <tr>
                        <th>Payout Name</th>
                        <th>Country name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
