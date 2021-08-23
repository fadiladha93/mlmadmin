@extends('affiliate.layouts.main')

@section('main_content')
    <div class="row">
        <div class="col-md-12">

            <div class="m-portlet" style="margin-top:20px;">
                <div class="m-portlet__body" style="padding:15px;">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            {{-- <img src="/assets/images/{{$promo->top_banner_img}}" class="buyPhotobook2020" width="100%"/> --}}
                            <img src="/assets/images/xcc-sub-1.png" class="buyPhotobook2020"
                                 width="60%"/>
                        </div>
                    </div>


                    @if($useraccess)
                        <!-- Do nothing -->
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row m-row--no-padding">
                                    <div class="col-md-4 text-center">
                                        <div class="text-center ticket-img" style="padding:15px;">
                                            <img class="img-fluid" style="width: 100%"
                                                 src="/assets/images/PHOTO-2020-02-28-21-04-49.jpg"/>
                                            <div class="pt-4">
                                                <button id="btnCheckOutVideoSeries"
                                                        class="btn btn-focus m-btn m-btn--pill m-btn--air btn-info">BUY NOW
                                                </button>
                                                <h2 class="strike-thru-price pt-4" style="font-size: 1.2em">Reg $99/mo</h2>
                                                <h2 class="shiny-new-price">NOW $19.95/mo</h2>
                                                <p>First 6 months are Free</p>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 offset-md-1">
                                        <div class="ticket-img" style="padding:15px;">
                                            <p style="font-size: 1.44em">&bull; 6 HOURS OF SOLID TRAINING TO START</p>
                                            <p style="font-size: 1.44em">&bull; Mr. Holton Buggs and Mr. Edwin Haynes<br>and
                                                all of the Blue Diamonds from Xccelerate</p>
                                            <p style="font-size: 1.44em">&bull; Hours of new trainings will be added every
                                                month</p>
                                            <p style="font-size: 1.44em">&bull; Private Access to the CEO Secret Vault of
                                                Trainings</p>
                                            <p style="font-size: 1.44em">&bull; Regular $99 per month - Special Price of
                                                $19.95 per month</p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12 text-center pt-2" style="font-size: 1.8em">
                                CHECK OUT THE VIDEOS BELOW
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <div class="m-portlet mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($useraccess)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://player.vimeo.com/video/394570445" width="640" height="360"
                                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="/assets/images/lock.jpeg"/>
                                <h3>You don't have access to this video.</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-12 text-center">
                            <h3>Holton Buggs</h3>
                        </div>
                    </div>
                </div>
            </div>


            <div class="m-portlet mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($useraccess)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://player.vimeo.com/video/394549567" width="640" height="360"
                                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="/assets/images/lock.jpeg"/>
                                <h3>You don't have access to this video.</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-12 text-center">
                            <h3>Edwin Haynes</h3>
                        </div>
                    </div>
                </div>
            </div>


            <div class="m-portlet mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($useraccess)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://player.vimeo.com/video/394545495" width="640" height="360"
                                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="/assets/images/lock.jpeg"/>
                                <h3>You don't have access to this video.</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-12 pt-4">
                            <h3>Cesar Munoz</h3>
                        </div>
                    </div>
                </div>
            </div>


            <div class="m-portlet mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($useraccess)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://player.vimeo.com/video/394542838" width="640" height="360"
                                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="/assets/images/lock.jpeg"/>
                                <h3>You don't have access to this video.</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-12 text-center">
                            <h3>Peter Hirsch &amp; Karen Hirsch</h3>
                        </div>
                    </div>
                </div>
            </div>


            <div class="m-portlet mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($useraccess)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://player.vimeo.com/video/395012606" width="640" height="360"
                                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="/assets/images/lock.jpeg"/>
                                <h3>You don't have access to this video.</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-12 pt-4">
                            <h3>Johnny Wimbrey</h3>
                        </div>
                    </div>
                </div>
            </div>


            <div class="m-portlet mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($useraccess)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://player.vimeo.com/video/395017861" width="640" height="360"
                                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="/assets/images/lock.jpeg"/>
                                <h3>You don't have access to this video.</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-12 pt-4">
                            <h3>Jose Luis</h3>
                        </div>
                    </div>
                </div>
            </div>


            <div class="m-portlet mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($useraccess)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://player.vimeo.com/video/395281418" width="640" height="360"
                                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="/assets/images/lock.jpeg"/>
                                <h3>You don't have access to this video.</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-12 text-center">
                            <h3>Rodolfo & Esteban</h3>
                        </div>
                    </div>
                </div>
            </div>


            <div class="m-portlet mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($useraccess)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://player.vimeo.com/video/395309786" width="640" height="360"
                                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="/assets/images/lock.jpeg"/>
                                <h3>You don't have access to this video.</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-12 text-center">
                            <h3>Abdrahmane Khoma</h3>
                        </div>
                    </div>
                </div>
            </div>


            <div class="m-portlet mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($useraccess)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://player.vimeo.com/video/395315010" width="640" height="360"
                                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="/assets/images/lock.jpeg"/>
                                <h3>You don't have access to this video.</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-12 text-center">
                            <h3>Earlene Buggs</h3>
                        </div>
                    </div>
                </div>
            </div>


            <div class="m-portlet mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            @if($useraccess)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe src="https://player.vimeo.com/video/395320863" width="640" height="360"
                                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="/assets/images/lock.jpeg"/>
                                <h3>You don't have access to this video.</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row pt-4">
                        <div class="col-md-12 text-center">
                            <h3>David Manning</h3>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection
