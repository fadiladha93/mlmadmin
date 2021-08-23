@extends('admin.layouts.main')

@section('main_content')

    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Rank Timing Settings
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <p>Select the times you wish the ranks calculations to run. Do not schedule times less than 3 hours
                    apart.</p>
                <div class="m-form">
                    <form id="rankTimingForm">
                        {{ csrf_field() }}
                        <div class="m-portlet__body">
                            <div class="row">
                                <input data-hour="0" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(0, $hours) ? 'checked' : '' ?> data-on="12:00 AM"
                                       data-off="12:00 AM">
                                <input data-hour="1" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(1, $hours) ? 'checked' : '' ?> data-on="1:00 AM" data-off="1:00 AM">

                                <input data-hour="2" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(2, $hours) ? 'checked' : '' ?> data-on="2:00 AM" data-off="2:00 AM">

                                <input data-hour="3" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(3, $hours) ? 'checked' : '' ?> data-on="3:00 AM" data-off="3:00 AM">

                                <input data-hour="4" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(4, $hours) ? 'checked' : '' ?> data-on="4:00 AM" data-off="4:00 AM">

                            </div>
                            <div class="row">
                                <input data-hour="5" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(5, $hours) ? 'checked' : '' ?> data-on="5:00 AM" data-off="5:00 AM">

                                <input data-hour="6" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(6, $hours) ? 'checked' : '' ?> data-on="6:00 AM" data-off="6:00 AM">

                                <input data-hour="7" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(7, $hours) ? 'checked' : '' ?> data-on="7:00 AM" data-off="7:00 AM">

                                <input data-hour="8" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(8, $hours) ? 'checked' : '' ?> data-on="8:00 AM" data-off="8:00 AM">

                                <input data-hour="9" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(9, $hours) ? 'checked' : '' ?> data-on="9:00 AM" data-off="9:00 AM">

                            </div>
                            <div class="row">
                                <input data-hour="10" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(10, $hours) ? 'checked' : '' ?> data-on="10:00 AM"
                                       data-off="10:00 AM">
                                <input data-hour="11" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(11, $hours) ? 'checked' : '' ?> data-on="11:00 AM"
                                       data-off="11:00 AM">
                                <input data-hour="12" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(12, $hours) ? 'checked' : '' ?> data-on="12:00 PM"
                                       data-off="12:00 PM">
                                <input data-hour="13" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(13, $hours) ? 'checked' : '' ?> data-on="1:00 PM"
                                       data-off="1:00 PM">

                                <input data-hour="14" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(14, $hours) ? 'checked' : '' ?> data-on="2:00 PM"
                                       data-off="2:00 PM">

                            </div>
                            <div class="row">
                                <input data-hour="15" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(15, $hours) ? 'checked' : '' ?> data-on="3:00 PM"
                                       data-off="3:00 PM">

                                <input data-hour="16" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(16, $hours) ? 'checked' : '' ?> data-on="4:00 PM"
                                       data-off="4:00 PM">

                                <input data-hour="17" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(17, $hours) ? 'checked' : '' ?> data-on="5:00 PM"
                                       data-off="5:00 PM">

                                <input data-hour="18" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(18, $hours) ? 'checked' : '' ?> data-on="6:00 PM"
                                       data-off="6:00 PM">

                                <input data-hour="19" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(19, $hours) ? 'checked' : '' ?> data-on="7:00 PM"
                                       data-off="7:00 PM">

                            </div>
                            <div class="row">
                                <input data-hour="20" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(20, $hours) ? 'checked' : '' ?> data-on="8:00 PM"
                                       data-off="8:00 PM">

                                <input data-hour="21" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(21, $hours) ? 'checked' : '' ?> data-on="9:00 PM"
                                       data-off="9:00 PM">

                                <input data-hour="22" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(22, $hours) ? 'checked' : '' ?> data-on="10:00 PM"
                                       data-off="10:00 PM">

                                <input data-hour="23" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= in_array(23, $hours) ? 'checked' : '' ?> data-on="11:00 PM"
                                       data-off="11:00 PM">

                                <input data-hour="24" type="checkbox"
                                       data-toggle="toggle"
                                       class="checkbox-toggle" data-size="small" data-width="110"
                                       <?= $has2359 === true ? 'checked' : '' ?> data-on="11:59 PM" data-off="11:59 PM">
                            </div>
                        </div>
                        <button id="btnSaveRankSettings" class="btn btn-success btn-sm m-btn--air" type="button">Save
                        </button>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        .row {
            padding-bottom: 5px;
        }

        .toggle {
            margin-left: 1em;
        }

        #btnSaveRankSettings {
            margin-left: 15vw;
        }
    </style>
@endsection
