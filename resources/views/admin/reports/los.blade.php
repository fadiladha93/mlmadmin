@extends('admin.layouts.main')
    @push('styles')
    <link href="{{asset('/css/los.css?')}}" rel="stylesheet" type="text/css" />
    @endpush
@section('main_content')
<div class="m-content">
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        LINE OF SPONSORSHIP REPORT
                    </h3>
                </div>
            </div>
        </div>
        <div class="row">
            @if(session('error'))
            <div class="container">
                <div class="alert alert-danger col-8 mt-2">
                    <p>{{session('error')}}</p>
                </div>
            </div>
            @endif
            <div class="col-md-8 col-lg-6">
                <form action="{{ url('/report/line-of-sponsorship') }}" method="post">
                    @csrf
                    <div class="m-portlet__body">
                        <div class="m-form m-form__section--first m-form--label-align-right">
                            <div class="form-group m-form__group row">
                                <label class="col-md-6 col-form-label">Enter distributor or username</label>
                                <div class="input-group col-md-6">
                                    <input class="form-control" name="distid" value="{{ request()->session()->get('distid') }}">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-seconday" type="submit"><i class="la la-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-6 col-form-label">Number of Upline Distributors</label>
                                <div class="col-md-6">
                                    <input class="form-control" value="{{  isset($response['numSponsors']) ?  $response['numSponsors'] : ''}}" disabled>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-6 col-form-label">ncrease level 4</label>
                                <div class="input-group col-md-6">
                                    <input class="form-control"  value="{{  isset($response['Sapphire']) ?  $response['Sapphire'] : ''}}" disabled>
                                    <div class="input-group-prepend">
                                        <a id="{{  isset($response['Sapphire']) ?  $response['Sapphire'] : ''}}" class="btn btn-outline-seconday bg-secondary"><i class="la la-info-circle"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-6 col-form-label">ncrease level 6</label>
                                <div class="input-group col-md-6">
                                    <input class="form-control" value="{{  isset($response['Ruby']) ?  $response['Ruby'] : ''}}" disabled>
                                    <div class="input-group-prepend">
                                        <a id="{{  isset($response['Ruby']) ?  $response['Ruby'] : ''}}" class="btn btn-outline-seconday bg-secondary"><i class="la la-info-circle"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-6 col-form-label">ncrease level 9</label>
                                <div class="input-group col-md-6">
                                    <input class="form-control" value="{{  isset($response['Emerald']) ?  $response['Emerald'] : ''}}" disabled>
                                    <div class="input-group-prepend">
                                        <a id="{{  isset($response['Emerald']) ?  $response['Emerald'] : ''}}" class="btn btn-outline-seconday bg-secondary"><i class="la la-info-circle"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-md-6 col-form-label">ncrease level 12</label>
                                <div class="input-group col-md-6">
                                    <input class="form-control" value="{{  isset($response['Diamond']) ?  $response['Diamond'] : ''}}" disabled>
                                    <div class="input-group-prepend">
                                        <a id="{{  isset($response['Diamond']) ?  $response['Diamond'] : ''}}" class="btn btn-outline-seconday bg-secondary"><i class="la la-info-circle"></i></a>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="ml-5 btn btn-primary mb-4" hidden>Submit</button>
                            <div class="row align-items-center col-md-6 mb-2">
                                <img src="{{asset('/assets/images/user4.jpg')}}" class="mr-2" alt="" style="border-radius: 50%; border:2px solid; border-color: #3db64d;" width="30px">
                                
                                <span>Active</span> 
                            </div>
                            <div class="row align-items-center col-md-6 mb-2">
                                <img src="{{asset('/assets/images/user4.jpg')}}" class="mr-2" alt="" style="border-radius: 50%; border:2px solid; border-color: #eb2c20;" width="30px">
                                <span>Inactive</span> 
                            </div>
                            <div class="row  align-items-center col-md-6">
                                <img src="{{asset('/assets/images/user4.jpg')}}" class="mr-2" alt="" style="border-radius: 50%; border:2px solid; border-color: #b8b8b8;" width="30px">
                                <span>Terminated</span> 
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @if(isset($response['users']))
                @php $i = count($response['users']); @endphp
                <div class="col-lg-6 col-md-7 col-sm-12 anyClass mb-5 mt-4">
                    <div class="m-portlet__body">
                        <div class="m-form m-form__section--first m-form--label-align-right">
                            <div class="mt-5"></div>
                            @foreach ($response['users'] as $user)
                            <div class="row align-items-center border-bottom user" id="distid{{ $user->distid }}">
                                <div class="col-md-8 mt-2 user-id"id="{{ $user->distid }}">
                                    <div class="row align-items-center">
                                        <div class="col-1 text-primary font-weight-bold">
                                            <h5>{{ $i = $i-1 }}</h5>
                                        </div>
                                        <div class="col-1 mr-3">
                                            @if ($user->account_status == 'TERMINATED')
                                                <img src="{{$user->profile_image_url ? \Storage::URL($user->profile_image_url) : asset('/assets/images/user4.jpg')}}"  alt="" class="terminated" width="35px">
                                            @elseif($user->getCurrentActiveStatus())
                                                <img src="{{$user->profile_image_url ? \Storage::URL($user->profile_image_url) : asset('/assets/images/user4.jpg')}}"  alt="" class="active" width="35px">
                                            @else
                                                <img src="{{$user->profile_image_url ? \Storage::URL($user->profile_image_url) : asset('/assets/images/user4.jpg')}}"  alt="" class="inactive" width="35px">
                                            @endif
                                        </div>
                                        <div class="mt-3 col-8">
                                            <ul class="list-unstyled">
                                                <li><span class="text-primary text-right font-weight-bold">NAME:</span> {{ $user->firstname }} {{ $user->lastname }}</li>
                                                <li><span class="text-primary text-right font-weight-bold">RANK:</span> {{ empty(\App\UserRankHistory::getCurrentMonthUserInfo($user->id)->achieved_rank_desc) ? '' : \App\UserRankHistory::getCurrentMonthUserInfo($user->id)->achieved_rank_desc }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="distributor-details-user1" id="details-user{{ $user->distid }}">
                                        <div class="details-wrap">
                                            <div class="details-title">Details</div>
                                            <div class="details-row">
                                                <div class="label-bviewer">TSA#</div>
                                                <div class="value">{{ $user->distid }}</div>
                                            </div>
                                            <div class="details-row">
                                                <div class="label-bviewer">USERNAME</div>
                                                <div class="value">{{ $user->username }}</div>
                                            </div>
                                            <div class="details-row">
                                                <div class="label-bviewer">Phone</div>
                                                <div class="value">{{ $user->phonenumber }}</div>
                                            </div>
                                            <div class="details-row">
                                                <div class="label-bviewer">Email</div>
                                                <div class="value">{{ $user->email }}</div>
                                            </div>
                                            <div class="details-row">
                                                <div class="label-bviewer">Country</div>
                                                <div class="value">{{ $user->country_code }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="mb-5"></div>
                            <button class="btn btn-primary mt-3" id="back"><i class="la la-arrow-left"></i></button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
    $( document ).ready(function() {
        $('#back').hide();

        $('.anyClass').animate({
            scrollTop: $(this).height() // aqui introduz o numero de px que quer no scroll, neste caso é a altura da propria div, o que faz com que venha para o fim
        }, 100);

        $(".user-id").hover(function(){
            $('#details-user'+this.id).show();
        },function(){
            $('#details-user'+this.id).hide();
        });

        $(".bg-secondary").click(function(){
            $('.user').hide();
            $('#distid'+this.id).show();
            $('#back').show();
            $('html, body').animate({
                scrollTop: 720
            }, 800);
        });

        $("#back").click(function(){
            $('.user').show();
            $('#back').hide();
        });

    });
    </script>
@endpush