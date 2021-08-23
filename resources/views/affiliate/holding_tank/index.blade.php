@extends('affiliate.layouts.main')

@section('main_content')
<div class="row">
    <div class="col-md-12">
        <div class="m-portlet" style="margin-top:20px;">
            <div class="m-portlet__head our_head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Placement lounge
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body holding-tank-page">
                <div class="container">
                    <div class="row" id="place-select-section">
                        <div class="col-md-6 form_sec">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="placementInfo"></label>
                                            <input type="text" class="form-control" id="placementInfo" placeholder="Loading..." disabled="disabled">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="placeOptionsControl"></label>
                                            <select class="form-control" id="placeOptionsControl">
                                                <option value="right">Right</option>
                                                <option value="left">Left</option>
                                                <option value="auto">Auto-Place</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button id="select-placement-btn" class="btn m-btn--pill btn-info m-btn m-btn--custom">Place Selected</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div style="padding: 15px;background-color: #e4f2fc;border-radius: 5px;">
                                This is your placement Lounge. Use the search feature to find the TSA# of the person you would like to place in the 
                                tree, select Left or Right , and then press the place button. Keep in mind that all the placements are in real-time, and 
                                can not be undone. If someone above you places someone in the same leg before you do, their placement will come first.
                                Let's start building!
                            </div>
                        </div>
                    </div>
                    <div class="row" id="pending-section" style="padding: 0 15px;">
                        <div class="col-12">
                            <div class="pending-table-wrap">
                                <table class="table pending-table" style="display: none;">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">TSA Number</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Enrollment Date</th>
                                        <th scope="col">Class</th>
                                        <th scope="col">Lifetime Rank</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="pending-user-row"></tr>
                                    </tbody>
                                </table>
                                <p class="multiple-select-placeholder" style="display: none;">Multiple distributors selected</p>
                                <p class="no-select-placeholder">No distributors selected</p>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding:0 25px;align-items:center;">
                        <div style="margin:25px 35px 25px 14px;">
                            <form method="GET"
                                  action="{{url('placement-lounge')}}">
                                @csrf
                                <div class="row">
                                    <div class="m-input-icon m-input-icon--right table-search">
                                        <input class="form-control form-control-sm" type="text" name="distributor_search" value="{{ $distributorSearch }}" placeholder="Search" autocomplete="off"/>
                                        <button type="submit" class="m-input-icon__icon m-input-icon__icon--right"><i class="la la-search"></i></button>
                                        <a href="{{url('placement-lounge')}}" class="reset-icon"><i class="la la-times"></i></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-title">Available to place</div>
                    </div>
                    <div class="row">
                        <div class="col-12" style="padding:0 25px;overflow-x: auto;">
                            @if (count($distributors) > 0)
                                <table class="table table-striped- table-hover table-checkable" id="js-holding-tank-distrib-table">
                                    <thead>
                                    <tr>
                                        <th class="checkbox-column">
                                            <div class="custom-control custom-checkbox" id="checkbox-dist-thead">
                                                <input type="checkbox" class="custom-control-input">
                                                <label class="custom-control-label" for="checkbox-thead"></label>
                                            </div>
                                        </th>
                                        <th class="tsa-column">TSA Number</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Username</th>
                                        <th class="date-column">Enrollment Date</th>
                                        <th>Class</th>
                                        <th>Lifetime Rank</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distributors as $distributor)
                                            <tr data-id="{{$distributor->id}}">
                                                <td data-checkbox="checkbox" class="checkbox-column">
                                                    <div class="custom-control custom-checkbox">
                                                        <input data-id="{{$distributor->id}}" type="checkbox" class="custom-control-input check-dist-row" id="checkbox-{{$distributor->id}}">
                                                        <label class="custom-control-label" for="checkbox-{{$distributor->id}}"></label>
                                                    </div>
                                                </td>
                                                <td data-distid="{{$distributor->distid}}" class="tsa-column">{{$distributor->distid}}</td>
                                                <td data-firstname="{{$distributor->firstname}}">{{$distributor->firstname}}</td>
                                                <td data-lastname="{{$distributor->lastname}}">{{$distributor->lastname}}</td>
                                                <td data-username="{{$distributor->username}}">{{$distributor->username}}</td>
                                                <td data-enrollment="{{ $distributor->getEnrolledDate() }}" class="date-column">
                                                    {{ $distributor->getEnrolledDate() }}
                                                </td>
                                                <td data-classname="{{ $distributor->product ? $distributor->product->productname : 'No Product' }}">{{ $distributor->product ? $distributor->product->productname : 'No Product' }}</td>
                                                <td data-rank="Emerald">{{$distributor->rank()->rankdesc}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                            <p class="no-available-distributors">No data to display</p>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="holdingTankWarningModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                This is a permanent placement and can NOT be undone. Are you sure about placement?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" id="warningModalContinueBtn" class="btn submit-btn btn-success">Yes</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->

                <!-- Modal -->
                <div class="modal fade" id="holdingTankSuccessModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header error">
                                <h5 class="modal-title">Error</h5>
                            </div>
                            <div class="modal-body"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn submit-btn btn-success" data-dismiss="modal">Ok</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->
            </div>
        </div>
    </div>
</div>

<script>
    var placementOptions = @json($options);
    var csrfToken = '{{ csrf_token() }}';
    var baseUrl = '{{url('/')}}';
</script>

@endsection

{{-- Includes all related to the template javascript modules --}}
@section('scripts')
    <script src="{{asset('/assets/js/modules/holding-tank/index.js')}}" type="text/javascript"></script>
@endsection




