@include('front.layout.header2')



<!-- Pagewrap Start Here -->
<div class="content">
    <!--  Banner Start here -->
    <section class="book-ride-banner inner-banner">
        <div class="banner-box">
            <h1><span>order </span>history</h1>

        </div>
    </section>
    <!--  Banner End here -->

    <!-- Search Section Start here -->
    <div class="col">
        <section class="search-section">
            <div class="container">
                <div class="content">
                    <!-- Tab links -->
                    <div class="tabs btnn">
                        <a class="tablinks active" href="{!! route('front.pending') !!}">
                            <p data-title="pending">Pending</p>
                        </a>
                        <a class="tablinks" href="{!! route('front.upcoming') !!}">
                            <p data-title="upcoming">Upcoming</p>
                        </a>
                        <a class="tablinks" href="{!! route('front.past') !!}">
                            <p data-title="past">Past</p>
                        </a>
                        <a class="tablinks" href="{!! route('front.book') !!}">
                            <p data-title="new">New+</p>
                        </a>
                    </div>

                    <!-- Tab content -->
                    <div class="wrapper_tabcontent">
                        <div id="pending" class="tabcontent active">
                            <div class="tab-sec left" id="">
                                <div class="row">
                                    <table class="table table-striped table-hover table-dark ">
                                        @if(isset($result) && count($result) > 0)
                                            @foreach($result as $i => $row)
                                                <tr>
                                                    <td>
                                                        {!! $row->booking_number !!} <small>({!! frontBookingType($row->booking_type) !!})</small>
                                                        <div class="smalll"><small>Created: {!! dateFormat($row->created_at, 'M d, Y h:i A') !!}</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {!! dateFormat($row->transfer_datetime, 'M d, Y h:i A') !!}
                                                        @if($row->is_return_way == 1)
                                                            <div class="smalll">
                                                                <small>Return: </small> {!! dateFormat($row->return_datetime, 'M d, Y h:i A') !!}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    </td>
                                                    <td><small>From :</small> {!! $row->from_location !!}<div class="smalll"><small>To :</small>
                                                            {!! $row->to_location !!}</div>
                                                    </td>
                                                    <td>
                                                        <div>Van <img src="{!! asset('front/images/visitors.svg') !!}">x4 </div>
                                                    </td>
                                                    <td>
                                                        <div id="accordion">
                                                            <div class="">
                                                                <div class="" id="headingOne">
                                                                    <h5 class="mb-0">
                                                                        <a class="btn book-ride" data-toggle="collapse"
                                                                            data-target="#collapse{!! $i !!}" aria-expanded="true"
                                                                            aria-controls="collapseOne">View Offers <i
                                                                                class="details"></i> <span
                                                                                class="offe">{!! $row->total_quotation !!}</span></a>

                                                                    </h5>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr id="collapse{!! $i !!}" class="collapse hide">
                                                    <td colspan="5">
                                                        <div style="width: 100%;">
                                                            <div class="collapse show" aria-labelledby="heading{!! $i !!}"
                                                                data-parent="#accordion" style="background:#fff">
                                                                <div class="card-body row">
                                                                    <div class="col-md-8">

                                                                        @if($row->is_flight || $row->is_meeting || $row->is_promo_code)
                                                                            <div class="route">
                                                                                <div class="row" style="color: black;">
                                                                                    @if($row->is_flight)
                                                                                        <div class="col-md-4">
                                                                                            <span>Flight or train number</span><br>{!! $row->flight_no !!}
                                                                                        </div>
                                                                                    @endif

                                                                                    @if($row->is_meeting)
                                                                                        <div class="col-md-4">
                                                                                            <span>Name Sign</span><br>{!! $row->passenger_name !!}
                                                                                        </div>
                                                                                    @endif

                                                                                    @if($row->is_promo_code)
                                                                                        <div class="col-md-4">
                                                                                            <span>Promo code</span><br>{!! $row->promo_code !!}
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <br>
                                                                        @endif

                                                                        <div class="form-group">
                                                                            <button type="submit" class="btn btn-danger">Cancel Ride</button>
                                                                            <button type="submit" class="btn btn-info">Edit Ride</button>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <iframe
                                                                            src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d1773182.221176982!2d75.40930041836052!3d29.770512315577353!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x391a837462345a7d%3A0x681102348ec60610!2sLudhiana%2C%20Punjab!3m2!1d30.900965!2d75.8572758!4m5!1s0x390cfd3c113a7b05%3A0xf8913afee1665916!2sNew%20Delhi%20Railway%20Station%2C%20Bhavbhuti%20Marg%2C%20Ratan%20Lal%20Market%2C%20Kamla%20Market%2C%20Ajmeri%20Gate%2C%20New%20Delhi%2C%20Delhi!3m2!1d28.642891499999998!2d77.2190894!5e0!3m2!1sen!2sin!4v1594463226630!5m2!1sen!2sin"
                                                                            width="100%" height="200" frameborder="0" style="border:0;"
                                                                            allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                                                    </div>
                                                                </div>

                                                                @if($row->total_quotation > 0)
                                                                    <div class="col-md-12">
                                                                        <p>
                                                                            <h4>You can chose one of the following options 
                                                                                <span style="border:1px solid #333">&darr;</span>
                                                                            </h4>
                                                                        </p>
                                                                    </div>
                                                                    <br>

                                                                    @foreach(bookingQuotation($row->id) as $q)
                                                                        <div class="row choices">
                                                                            <div class="col-md-2 car">
                                                                                <div class="gallery-item">

                                                                                    <a data-fancybox="gallery" href="{!! asset('front/images/swift1.jpg') !!}"><img
                                                                                            src="{!! asset('front/images/swift1.jpg') !!}" alt="Gallery Thumb">
                                                                                        <div class="overlay">
                                                                                            <div>
                                                                                                <img src="{!! asset('front/images/zoom-image.png') !!}" alt="">
                                                                                            </div>
                                                                                        </div>
                                                                                    </a>
                                                                                    <a data-fancybox="gallery" href="{!! asset('front/images/swift2.jpg') !!}"><img
                                                                                            src="{!! asset('front/images/swift2.jpg') !!}" alt="Gallery Thumb"
                                                                                            style="display:none">
                                                                                    </a>
                                                                                    <a data-fancybox="gallery" href="{!! asset('front/images/swift3.jpg') !!}"><img
                                                                                            src="{!! asset('front/images/swift3.jpg') !!}" alt="Gallery Thumb"
                                                                                            style="display:none">
                                                                                    </a>
                                                                                    <a data-fancybox="gallery" href="{!! asset('front/images/swift4.jpg') !!}"><img
                                                                                            src="{!! asset('front/images/swift4.jpg') !!}" alt="Gallery Thumb"
                                                                                            style="display:none">
                                                                                    </a>

                                                                                </div>

                                                                            </div>
                                                                            <div class="col-md-3" style="color: black;">
                                                                                <h4>Toyota Innova, 2016</h4>
                                                                                <div><strong>Van</strong> <img src="{!! asset('front/images/visitors.svg') !!}" />x4 <img
                                                                                        src="{!! asset('front/images/bag.svg') !!}" />x6</div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <h4>Languages Known : <small>English, Hindi, Punjabi</small></h4>
                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <h4>₹{!! numberFormat($q->quotation_amount) !!}</h4>
                                                                            </div>
                                                                            <div class="col-md-2" style="text-align:center">
                                                                                <a data-toggle="modal" data-target="#book"
                                                                                    class="btn book-ride">Book <img src="{!! asset('front/images/star.png') !!}" /></a>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                    <br>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Search Section End here -->

    <!-- Modal -->
    <div class="modal fade" id="book" role="dialog" style="z-index:9999999999999999999999999">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="login-form">
                        <div class="top-sec">
                            <a href="index.html" class="logo"><img src="images/logo3.png" alt="Jeetii"
                                    title="Jeetii"></a>
                            <h3>Transfer price is: ₹140,000.00</h3>
                            <p>By pressing the button you accept the terms of Our Service Agreement</p>
                            <p>Service fee 2%</p>
                            <a href="" class="btn book-ride">Book Now</a>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="clearfix"></div>

    @include('front.layout.footer')