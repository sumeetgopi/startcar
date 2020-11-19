@include('front.layout.header')

<!-- Pagewrap Start Here -->
<div class="content">
    <!--  Banner Start here -->
    <section class="banner-home">
        <div class="banner-box">

        </div>
    </section>
    <!--  Banner End here -->
    <!-- Search Section Start here -->
    <section class="search-section">
        <div class="container">
            <div class="content" style="position:relative; margin-top:-190px; z-index:8">
                <!-- Tab links -->
                <div class="tabs" style="">
                    <button class="tablinks active" data-country="route">
                        <p data-title="route">Book By Route</p>
                    </button>
                    <button class="tablinks" data-country="hour">
                        <p data-title="hour">Book Per Hour</p>
                    </button>
                </div>

                <!-- Tab content -->
                <div class="wrapper_tabcontent">
                    <div id="route" class="tabcontent active">
                        <div class="tab-sec" id="">
                            <form class="form-inline" action="{!! route('front.book') !!}" method="get">
                                <input type="hidden" name="t" value="route" />
                                <div class="form-group">
                                    <input type="text" name="fl" class="form-control" placeholder="Start Destination">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="tl" class="form-control" placeholder="End Destination">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="e" class="form-control" placeholder="Email ID">
                                </div>

                                <?php
                                    $bookingCategory = bookingCategory();
                                    $path = env('CATEGORY_PATH');
                                ?>
                                @if(isset($bookingCategory) && count($bookingCategory) > 0)
                                    <input type="hidden" value="{!! $bookingCategory[0]->id !!}" name="vc" class="__vc">
                                    <nav>
                                        <div class="nav nav-tabs nav-fill __nav_vehicle_category __nav">
                                            @foreach($bookingCategory as $bci => $bc)
                                                <a class="nav-item nav-link {!! ($bci == 0) ? 'active' : '' !!}" href="#{!! $bc->category_name !!}" data-value="{!! $bc->id !!}">
                                                    {!! frontWebImg($path, $bc->category_image) !!}
                                                    <p>{!! $bc->category_name !!}</p>
                                                </a>
                                            @endforeach
                                        </div>
                                    </nav>
                                @endif

                              
                                <div class="form-group">
                                    <button type="submit" class="btn book-ride">Book a ride</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="hour" class="tabcontent">
                        <div class="tab-sec" id="">
                            <form class="form-inline" action="{!! route('front.book') !!}" method="get">
                                <input type="hidden" name="t" value="hour">
                                <div class="form-group">
                                    <input type="text"  name="fl" class="form-control" placeholder="Start Destination">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="e" class="form-control" placeholder="Email ID">
                                </div>

                                <?php
                                    $bookingCategory = bookingCategory();
                                    $path = env('CATEGORY_PATH');
                                ?>
                                @if(isset($bookingCategory) && count($bookingCategory) > 0)
                                    <input type="hidden" value="{!! $bookingCategory[0]->id !!}" name="vc" class="__vc2">
                                    <nav>
                                        <div class="nav nav-tabs nav-fill __nav_vehicle_category2 __nav">
                                            @foreach($bookingCategory as $bci => $bc)
                                                <a class="nav-item nav-link {!! ($bci == 0) ? 'active' : '' !!}" href="#{!! $bc->category_name !!}" data-value="{!! $bc->id !!}">
                                                    {!! frontWebImg($path, $bc->category_image) !!}
                                                    <p>{!! $bc->category_name !!}</p>
                                                </a>
                                            @endforeach
                                        </div>
                                    </nav>
                                @endif

                                <div class="form-group">
                                    <button type="submit" class="btn book-ride">Book a ride</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Search Section End here -->


    <!-- What We Offer Section Start here -->
    <section class="we-offer sec-pd">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h3>WHAT WE OFFER</h3>
                        <h2>Welcome To Us</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vel dolor nec metus porta feugiat
                            sit amet vitae neque. Ut egestas venenatis massa, quis facilisis massa blandit vel.</p>
                    </div>
                </div>
            </div>
            <ul>
                <li>
                    <div class="offer-colm">
                        <img src="{!! asset('front/images/adress-pickup.png') !!}" alt="Address Pickup">
                        <h3>Address Pickup</h3>
                        <p>We always pick up our clients on time, 24/7 availability.</p>
                    </div>
                </li>
                <li>
                    <div class="offer-colm">
                        <img src="{!! asset('front/images/airport-ransfer.png') !!}" alt="Airport Transfer">
                        <h3>Airport Transfer</h3>
                        <p>GetCab specialized in 24 hours airport transfer service.</p>
                    </div>
                </li>
                <li>
                    <div class="offer-colm">
                        <img src="{!! asset('front/images/long-distance.png') !!}" alt="Long Distance">
                        <h3>Long Distance</h3>
                        <p>We offer you a long distance taxi service to anywhere.</p>
                    </div>
                </li>
                <li>
                    <div class="offer-colm no-bd">
                        <img src="{!! asset('front/images/taxi-tours.png') !!}" alt="Taxi Tours">
                        <h3>Taxi Tours</h3>
                        <p>We offer taxi tours of various durations and complexity.</p>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <!-- What We Offer Section End here -->


    <section class="fleet-carousel" id="fleet">
        <div class="col-md-12 col-sm-12">
            <div class="tj-heading-style">
                <h3>Our Fleet</h3>
            </div>
        </div>
        <div class="carousel-outer">
            <div class="cab-carousel" id="cab-carousel">
                <div class="fleet-item">
                    <img src="{!! asset('front/images/mini.png') !!}" alt="" />
                    <div class="fleet-inner">
                        <h4>Mini</h4>
                        <ul>
                            <li><img src="{!! asset('front/images/visitors-w.svg') !!}"> x 4</li>
                            <li><img src="{!! asset('front/images/bag-w.svg') !!}"> x 4</li>
                        </ul>
                    </div>
                </div>
                <div class="fleet-item">
                    <img src="{!! asset('front/images/sedan.png') !!}" alt="" />
                    <div class="fleet-inner">
                        <h4>Sedan</h4>
                        <ul>
                            <li><img src="{!! asset('front/images/visitors-w.svg') !!}"> x 6</li>
                            <li><img src="{!! asset('front/images/bag-w.svg') !!}"> x 6</li>
                        </ul>
                    </div>
                </div>
                <div class="fleet-item">
                    <img src="{!! asset('front/images/minibus.png') !!}" alt="" />
                    <div class="fleet-inner">
                        <h4>Mini Bus</h4>
                        <ul>
                            <li><img src="{!! asset('front/images/visitors-w.svg') !!}"> x 12</li>
                            <li><img src="{!! asset('front/images/bag-w.svg') !!}"> x 15</li>
                        </ul>
                    </div>
                </div>
                <div class="fleet-item">
                    <img src="{!! asset('front/images/bus.png') !!}" alt="" />
                    <div class="fleet-inner">
                        <h4>Bus</h4>
                        <ul>
                            <li><img src="{!! asset('front/images/visitors-w.svg') !!}"> x 50</li>
                            <li><img src="{!! asset('front/images/bag-w.svg') !!}"> x 60</li>
                        </ul>
                        <!--<strong class="price">Rs. 250<span> / hour</span></strong>
								<a href="">Book Now <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>-->
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Our Benefits Section Start here -->
    <section class="our-benefits sec-pd">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h3>MAIN FEATURES</h3>
                        <h2>Our Benefits</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="benefits-colm">
                        <img src="{!! asset('front/images/fixed-price.png') !!}" alt="Fixed Price">
                        <h3>Fixed Price</h3>
                        <p>The fixed fare is set in every taximeter as the main tariff.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefits-colm">
                        <img src="{!! asset('front/images/no-fee.png') !!}" alt="No Fee">
                        <h3>No Fee</h3>
                        <p>We guarantee fixed price and you should not pay tips.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefits-colm">
                        <img src="{!! asset('front/images/pleasure.png') !!}" alt="100% Pleasure">
                        <h3>100% Pleasure</h3>
                        <p>We have a lot of standing customer and high ratings.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="benefits-colm">
                        <img src="{!! asset('front/images/nationwide.png') !!}" alt="Nationwide">
                        <h3>Nationwide</h3>
                        <p>Our application is the easiest way to book a taxi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefits-colm">
                        <img src="{!! asset('front/images/easy-to-use.png') !!}" alt="Easy to use">
                        <h3>Easy to use</h3>
                        <p>Orci varius natoque penatibus et magnis dis parturient montes,mus.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="benefits-colm">
                        <img src="{!! asset('front/images/bonuse--for-ride.png') !!}" alt="100% Pleasure">
                        <h3>Earn Points on Rides</h3>
                        <p>Phasellus l et porta tortor dignissim at. Pellentesque gravida tortormollis.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Our Benefits Section End here -->


    <!-- Download App Section Start here -->
    <section class="download-app parallax">
        <div class="container">
            <div class="row">

                <div class="col-md-6">
                    <div class="section-title">
                        <h3>BECOME A DRIVER</h3>
                        <h2>EARN WITH US</h2>
                        <p>Quisque eleifend enim eu dui gravida scelerisque ac sed erat. Morbi sed ultrices lacus.
                            Vestibulum lacinia ipsum non placerat viverra.</p>
                        <h3>Download the App</h3>
                        <ul>
                            <li><a href="#" target="_blank"><img src="{!! asset('front/images/app-store.png') !!}"
                                        alt="App Store"></a></li>
                            <li><a href="#" target="_blank"><img src="{!! asset('front/images/google-app.png') !!}"
                                        alt="Google App"></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="download-app-right">
                        <img src="{!! asset('front/images/download-app-right-image.png') !!}" alt="Mobile App">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Download App Section End here -->


    <!-- Testimonials Section Start Here -->
    <section class="testimonials-sec sec-pd">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="section-title">
                        <h3>Our Client Loves Us</h3>
                        <h2>testimonials</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="testimonial-slider" class="owl-carousel owl-theme">
                        <div class="item">
                            <div class="testimonial">
                                <div class="pic">
                                    <img src="{!! asset('front/images/img-1.png') !!}" alt="">
                                </div>
                                <div class="review">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis mauris tellus,
                                        congue sit amet elementum sit amet, imperdiet non erat. Pellentesque finibus
                                        nisi rhoncus turpis pellentesque venenatis. Ut nec scelerisque urna, non luctus
                                        leo. Aliquam aliquam tempus lectus eu rutrum. Nam vitae nisi dapibus, bibendum
                                        metus rutrum eleifend lacus. Suspendisse potenti. Maecenas dolor nunc, interdum
                                        id metus sed, euismod tempor mi. Cras orci ex, faucibus in quam</p>
                                </div>
                            </div>
                            <div class="author">
                                <h3>John Deo</h3>
                                <p>Mauris venenatis pulvinar</p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonial">
                                <div class="pic">
                                    <img src="{!! asset('front/images/img-1.png') !!}" alt="">
                                </div>
                                <div class="review">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis mauris tellus,
                                        congue sit amet elementum sit amet, imperdiet non erat. Pellentesque finibus
                                        nisi rhoncus turpis pellentesque venenatis. Ut nec scelerisque urna, non luctus
                                        leo. Aliquam aliquam tempus lectus eu rutrum. Nam vitae nisi dapibus, bibendum
                                        metus rutrum eleifend lacus. Suspendisse potenti. Maecenas dolor nunc, interdum
                                        id metus sed, euismod tempor mi. Cras orci ex, faucibus in quam</p>
                                </div>
                            </div>
                            <div class="author">
                                <h3>John Deo</h3>
                                <p>Mauris venenatis pulvinar</p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonial">
                                <div class="pic">
                                    <img src="{!! asset('front/images/img-1.png') !!}" alt="">
                                </div>
                                <div class="review">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis mauris tellus,
                                        congue sit amet elementum sit amet, imperdiet non erat. Pellentesque finibus
                                        nisi rhoncus turpis pellentesque venenatis. Ut nec scelerisque urna, non luctus
                                        leo. Aliquam aliquam tempus lectus eu rutrum. Nam vitae nisi dapibus, bibendum
                                        metus rutrum eleifend lacus. Suspendisse potenti. Maecenas dolor nunc, interdum
                                        id metus sed, euismod tempor mi. Cras orci ex, faucibus in quam</p>
                                </div>
                            </div>
                            <div class="author">
                                <h3>John Deo</h3>
                                <p>Mauris venenatis pulvinar</p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimonial">
                                <div class="pic">
                                    <img src="{!! asset('front/images/img-1.png') !!}" alt="">
                                </div>
                                <div class="review">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis mauris tellus,
                                        congue sit amet elementum sit amet, imperdiet non erat. Pellentesque finibus
                                        nisi rhoncus turpis pellentesque venenatis. Ut nec scelerisque urna, non luctus
                                        leo. Aliquam aliquam tempus lectus eu rutrum. Nam vitae nisi dapibus, bibendum
                                        metus rutrum eleifend lacus. Suspendisse potenti. Maecenas dolor nunc, interdum
                                        id metus sed, euismod tempor mi. Cras orci ex, faucibus in quam</p>
                                </div>
                            </div>
                            <div class="author">
                                <h3>John Deo</h3>
                                <p>Mauris venenatis pulvinar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonials Section End Here -->

    @include('front.layout.footer')