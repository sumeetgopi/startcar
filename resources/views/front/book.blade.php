@include('front.layout.header2')



        <!-- Pagewrap Start Here -->
<div class="content">
    <!--  Banner Start here -->
    <section class="book-ride-banner inner-banner">
        <div class="banner-box">
            <h1><span>book </span>a ride</h1>

        </div>
    </section>
    <!--  Banner End here -->

    <!-- Search Section Start here -->
    <div class="col">
        <section class="search-section">
            <div class="container">
                <div class="content">
                    <!-- Tab links -->
                    <div class="tabs" style="">
                        <button class="tablinks {!! ($t == '' || $t == 'route') ? 'active' : '' !!}" data-country="route">
                            <p data-title="route">Book By Route</p>
                        </button>
                        <button class="tablinks {!! ($t == 'hour') ? 'active' : '' !!}" data-country="hour">
                            <p data-title="hour">Book Per Hour</p>
                        </button>
                    </div>

                    <!-- Tab content -->
                    <div class="wrapper_tabcontent">
                        <div id="route" class="tabcontent  {!! ($t == '' || $t == 'route') ? 'active' : '' !!}">
                            <div class="tab-sec" id="">
                                {!! Form::open(['route' => 'front.book-by-route', 'method' => 'post', 'id' => 'ajax-submit', 'files' => 'true', 'class' => 'form-inline']) !!}

                                <div>
                                    <div class="form-group">
                                        <input type="text" name="email" value="{!! $e !!}" class="form-control" placeholder="Email ID">
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group __mobile">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">+91</span>
                                            </div>
                                            <input type="text" name="mobile_number" value="{!! $m !!}" class="form-control" placeholder="Mobile Number">
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <input type="text" name="from_location" value="{!! $fl !!}" class="form-control" placeholder="Start Destination">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="to_location" value="{!! $tl !!}" class="form-control" placeholder="End Destination">
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <input readonly="readonly" type="text" name="transfer_date" class="form-control __from_date" value="{!! date('d-m-Y') !!}" placeholder="Transfer Date">
                                </div>
                                <div class="form-group">
                                    <input readonly="readonly" type="text" name="transfer_time" class="form-control timepicker" autocomplete="off" id="time" placeholder="Time">
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-check-input">
                                    <input type="checkbox" name="is_return_way" aria-label="Checkbox for following text input" id="return2"
                                               onclick="returnn()"> &nbsp; Add Return Way
                                </div>
                                <div id="reeturn" style="display:none">
                                    <div class="form-group">
                                        <input readonly="readonly" type="text" name="return_date" class="form-control __to_date" value="{!! date('d-m-Y') !!}" placeholder="Transfer Date">
                                    </div>
                                    <div class="form-group">
                                        <input readonly="readonly" type="text" name="return_time" class="form-control timepicker" autocomplete="off" id="time"
                                               value="" placeholder="Time" required="">
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <strong>No. of Adults</strong>
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-left-minus1 btn btn-danger btn-number"
                                                data-type="minus" data-field="">
                                            -
                                        </button>
                                    </span>
                                    <input type="text" name="no_of_adult" id="quantity1" class="form-control input-number"
                                           value="2" min="1" max="100">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-right-plus1 btn btn-success btn-number"
                                                data-type="plus" data-field="">
                                            +
                                        </button>
                                    </span>
                                </div>

                                <div class="form-group">
                                    <strong>No. of Children</strong>
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-left-minus2 btn btn-danger btn-number"
                                                data-type="minus" data-field="">
                                            -
                                        </button>
                                    </span>
                                    <input type="text" id="quantity2" name="no_of_children" class="form-control input-number"
                                           value="2" min="1" max="100">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-right-plus2 btn btn-success btn-number"
                                                data-type="plus" data-field="">
                                            +
                                        </button>
                                    </span>
                                </div>
                                <div class="clearfix"></div>

                                <?php
                                    $bookingCategory = bookingCategory();
                                    $path = env('CATEGORY_PATH');
                                ?>
                                @if(isset($bookingCategory) && count($bookingCategory) > 0)
                                    <input type="hidden" value="{!! ($vc != '') ? $vc : $bookingCategory[0]->id !!}" name="vehicle_category" class="__vc">
                                    <nav>
                                        <div class="nav nav-tabs nav-fill __nav_vehicle_category __nav">
                                            @foreach($bookingCategory as $bci => $bc)
                                                <a class="nav-item nav-link {!! ($bci == 0 && $vc == '') ? 'active' : $vc == $bc->id ? 'active' : '' !!}" href="#{!! $bc->category_name !!}" data-value="{!! $bc->id !!}">
                                                    {!! frontWebImg($path, $bc->category_image) !!}
                                                    <p>{!! $bc->category_name !!}</p>
                                                </a>
                                            @endforeach
                                        </div>
                                    </nav>
                                @endif


                                <div class="row form-group">
                                    <div class="col">
                                        <strong>Additional Options</strong>
                                    </div>
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="">
                                                <div class="input-group-text">
                                                    <input type="checkbox" name="is_flight"
                                                           aria-label="Checkbox for following text input" id="flight"
                                                           onclick="flightt()">&nbsp; Flight or train number &nbsp;<br>
                                                    <div> <input type="text" name="flight_number" id="flightno" style="display:none; animation-duration: 3s; animation-name: slidein;" placeholder="Flight / Train Number" class="form-control input-number"></div>
                                                </div>
                                                <div class="input-group-text">
                                                    <input type="checkbox" name="is_meeting"
                                                           aria-label="Checkbox for following text input" id="meeting"
                                                           onclick="meetingg()">&nbsp; Meeting with a name sign is required &nbsp; <br>
                                                    <div> <input type="text" name="passenger_name" id="meeeting" style="display:none"
                                                                 placeholder="Please fill the passenger's name"
                                                                 class="form-control input-number"></div>
                                                </div>
                                                <div class="input-group-text">
                                                    <input type="checkbox" name="is_promo_code"
                                                           aria-label="Checkbox for following text input" id="promo"
                                                           onclick="promoo()">&nbsp; Promo code &nbsp; <br>
                                                    <div> <input type="text" name="promo_code" id="promocode" style="display:none"
                                                                 placeholder="Enter promo code"
                                                                 class="form-control input-number"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="requirement" placeholder="Provide your requirements" rows="3" cols="50"
                                                  class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div>By pressing the button you accept the terms of Jeetii Service Agreement</div>
                                <div class="form-group">
                                    <button type="submit" class="btn book-ride">Book a ride</button>
                                </div>
                                {!! Form::close() !!}

                            </div>
                        </div>

                        <div id="hour" class="tabcontent {!! ($t == 'hour') ? 'active' : '' !!}">
                            <div class="tab-sec" id="">
                                {!! Form::open(['route' => 'front.book-per-hour', 'method' => 'post', 'id' => 'ajax-submit2', 'files' => 'true', 'class' => 'form-inline']) !!}
                                <div class="form-group">
                                    <input type="text" name="email" value="{!! $e !!}" class="form-control" placeholder="Email ID">
                                </div>
                                <div class="form-group">
                                    <div class="input-group __mobile">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+91</span>
                                        </div>
                                        <input type="text" name="mobile_number" value="{!! $m !!}" class="form-control" placeholder="Mobile Number">
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <input type="text" name="from_location" value="{!! $fl !!}" class="form-control" placeholder="Start Destination">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="to_location" class="form-control" placeholder="End Destination">
                                </div>
                                <div class="clearfix"></div>
                                <div><strong>Start of Transfer</strong></div>
                                <div class="form-group">
                                    <input readonly="readonly" type="text" name="transfer_date" class="form-control __from_date" value="{!! date('d-m-Y') !!}" placeholder="Date">
                                </div>
                                <div class="form-group">
                                    <input readonly="readonly" type="text" name="transfer_time" class="form-control timepicker" autocomplete="off" id="time" placeholder="Time">
                                </div>

                                <div><strong>End of Transfer</strong></div>
                                <div class="form-group">
                                    <input readonly="readonly" type="text" name="return_date" class="form-control __to_date" value="{!! date('d-m-Y') !!}" placeholder="Date">
                                </div>
                                <div class="form-group">
                                    <input readonly="readonly" type="text" class="form-control timepicker" autocomplete="off" id="time"
                                           name="return_time" value="" placeholder="Time" required="">
                                </div>

                                <div class="clearfix"></div>

                                <div class="form-group">
                                    <strong>No. of Adults</strong>
                                        <span class="input-group-btn">
                                            <button type="button" class="quantity-left-minus3 btn btn-danger btn-number"
                                                    data-type="minus" data-field="">
                                                -
                                            </button>
                                        </span>
                                    <input type="text" id="quantity3" name="no_of_adult"
                                           class="form-control input-number" value="2" min="1" max="100">
                                        <span class="input-group-btn">
                                            <button type="button" class="quantity-right-plus3 btn btn-success btn-number"
                                                    data-type="plus" data-field="">
                                                +
                                            </button>
                                        </span>
                                </div>


                                <div class="form-group">
                                    <strong>No. of Children</strong>
                                        <span class="input-group-btn">
                                            <button type="button" class="quantity-left-minus4 btn btn-danger btn-number"
                                                    data-type="minus" data-field="">
                                                -
                                            </button>
                                        </span>
                                    <input type="text" id="quantity4" name="no_of_children"
                                           class="form-control input-number" value="2" min="1" max="100">
                                        <span class="input-group-btn">
                                            <button type="button"
                                                    class="quantity-right-plus4 btn btn-success btn-number" data-type="plus"
                                                    data-field="">
                                                +
                                            </button>
                                        </span>
                                </div>

                                <div class="clearfix"></div>

                                @if(isset($bookingCategory) && count($bookingCategory) > 0)
                                    <input type="hidden" value="{!! ($vc != '') ? $vc : $bookingCategory[0]->id !!}" name="vehicle_category" class="__vc2">
                                    <nav>
                                        <div class="nav nav-tabs nav-fill __nav_vehicle_category2 __nav">
                                            @foreach($bookingCategory as $bci => $bc)
                                                <a class="nav-item nav-link {!! ($bci == 0 && $vc == '') ? 'active' : $vc == $bc->id ? 'active' : '' !!}"" href="#{!! $bc->category_name !!}" data-value="{!! $bc->id !!}">
                                                    {!! frontWebImg($path, $bc->category_image) !!}
                                                    <p>{!! $bc->category_name !!}</p>
                                                </a>
                                            @endforeach
                                        </div>
                                    </nav>
                                @endif

                                <div class="row form-group">
                                    <div class="col">
                                        <strong>Additional Options</strong>
                                    </div>
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="">
                                                <div class="input-group-text">
                                                    <input type="checkbox" name="is_flight"
                                                           aria-label="Checkbox for following text input" id="flight2"
                                                           onclick="flightt2()">&nbsp; Flight or train number &nbsp;<br>
                                                    <div> <input type="text" name="flight_number" id="flightno2" style="display:none; animation-duration: 3s; animation-name: slidein;" placeholder="Flight / Train Number" class="form-control input-number"></div>
                                                </div>
                                                <div class="input-group-text">
                                                    <input type="checkbox" name="is_meeting"
                                                           aria-label="Checkbox for following text input" id="meeting2"
                                                           onclick="meetingg2()">&nbsp; Meeting with a name sign is required &nbsp; <br>
                                                    <div> <input type="text" name="passenger_name" id="meeeting2" style="display:none"
                                                                 placeholder="Please fill the passenger's name"
                                                                 class="form-control input-number"></div>
                                                </div>
                                                <div class="input-group-text">
                                                    <input type="checkbox" name="is_promo_code"
                                                           aria-label="Checkbox for following text input" id="promo2"
                                                           onclick="promoo2()">&nbsp; Promo code &nbsp; <br>
                                                    <div> <input type="text" name="promo_code" id="promocode2" style="display:none"
                                                                 placeholder="Enter promo code"
                                                                 class="form-control input-number"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                            <textarea name="requirement" placeholder="Provide your requirements" rows="3" cols="50"
                                                      class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div>By pressing the button you accept the terms of Jeetii Service Agreement</div>
                                <div class="form-group">
                                    <button type="submit" class="btn book-ride">Book a ride</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Search Section End here -->

    <div class="col">
        <iframe
                src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d1773182.221176982!2d75.40930041836052!3d29.770512315577353!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x391a837462345a7d%3A0x681102348ec60610!2sLudhiana%2C%20Punjab!3m2!1d30.900965!2d75.8572758!4m5!1s0x390cfd3c113a7b05%3A0xf8913afee1665916!2sNew%20Delhi%20Railway%20Station%2C%20Bhavbhuti%20Marg%2C%20Ratan%20Lal%20Market%2C%20Kamla%20Market%2C%20Ajmeri%20Gate%2C%20New%20Delhi%2C%20Delhi!3m2!1d28.642891499999998!2d77.2190894!5e0!3m2!1sen!2sin!4v1594463226630!5m2!1sen!2sin"
                width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
                tabindex="0"></iframe>
    </div>

    <div class="clearfix"></div>





    <!-- Modal -->
    <div class="modal fade" id="login-form" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="login-form">
                        <div class="top-sec">
                            <a href="index.html" class="logo"><img src="{!! asset('front/images/logo3.png') !!}"
                                                                   alt="Jeetii" title="Jeetii"></a>
                            <h3>LOGIN</h3>
                            <p></p>
                            <div class="form-section">
                                <form action="#" method="get">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Email/Phone" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password" required>
                                    </div>
                                    <p class="frgot-pwd"><a href="#">Forgot password?</a></p>
                                    <button type="submit" class="btn book-ride submit">LOGIN</button>
                                </form>
                            </div>
                        </div>
                        <div class="bottom-sec">
                            <p>Don't have an account?<a data-toggle="modal" data-target="#sign-up" data-dismiss="modal">
                                    Register Now</a></p>
                            <p class="log-with"><span>or login with</span></p>
                            <ul class="login-socio">
                                <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i>Facebook</a></li>
                                <li><a class="twitter" href="#" target="_blank"><i
                                                class="fab fa-twitter"></i>Twitter</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="sign-up" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="sign-up-form">
                        <div class="top-sec">
                            <a href="index.html" class="logo"><img src="{!! asset('front/images/logo3.png" alt="Jeetii"
                                    title="Jeetii"></a>
                            <h3>REGISTER</h3>
                            <p></p>
                            <div class="form-section">
                                <form action="#" method="get">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Full name*" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="phone*" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="email*" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password" required>
                                    </div>
                                    <button type="submit" class="btn book-ride submit">REGISTER</button>
                                </form>
                            </div>
                        </div>
                        <div class="bottom-sec">
                            <p>Already have an account?<a data-toggle="modal" data-target="#login-form"
                                    data-dismiss="modal"> Login</a></p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>



    @include('front.layout.footer')