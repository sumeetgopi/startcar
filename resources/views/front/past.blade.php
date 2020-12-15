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
                        <a class="tablinks" href="{!! route('front.pending') !!}">
                            <p data-title="pending">Pending</p>
                        </a>
                        <a class="tablinks" href="{!! route('front.upcoming') !!}">
                            <p data-title="upcoming">Upcoming</p>
                        </a>
                        <a class="tablinks active" href="{!! route('front.past') !!}">
                            <p data-title="past">Past</p>
                        </a>
                        <a class="tablinks" href="{!! route('front.book') !!}">
                            <p data-title="new">New+</p>
                        </a>
                    </div>

                    <!-- Tab content -->
                    <div class="wrapper_tabcontent">
                        <div id="upcoming" class="tabcontent active">
                            <div class="tab-sec left" id="">

                                <div class="row">

                                    <table class="table table-striped table-hover table-dark ">
                                        @if(isset($result) && count($result) > 0)
                                            @foreach($result as $i => $row)
                                                <tr>
                                                    <td>
                                                        {!! $row->booking_number !!} <small>({!! frontBookingType($row->booking_type) !!})</small>
														<div class="smalll"><small>Created: {!! dateFormat($row->created_at, 'M d, Y h:i A') !!}</small></div>
													</td>
													<td>{!! dateFormat($row->transfer_datetime, 'M d, Y h:i A') !!}
														<div class="smalll">
														@if($row->is_return_way == 1)
															<small>Return: </small> {!! dateFormat($row->return_datetime, 'M d, Y h:i A') !!}
														@endif
													</td>
													<td><small>From :</small> {!! $row->from_location !!}<div class="smalll"><small>To :</small>
                                                            {!! $row->to_location !!}</div>
                                                    </td>
                                                    <td>
                                                        <div class="__myicon">
                                                            {!! $row->category_name !!} <br>

                                                            @if($row->no_of_adult > 0)
                                                                <div><img src="{!! asset('front/images/visitors.svg') !!}">x{!! $row->no_of_adult !!} <small>( Adult )</small> </div>
                                                            @endif

                                                            @if($row->no_of_children > 0)
                                                                <div><img src="{!! asset('front/images/visitors.svg') !!}">x{!! $row->no_of_children !!} <small>( Child )</small></div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="accordion">
                                                            <div class="">
                                                                <div class="" id="headingOne">
                                                                    <h5 class="mb-0">
                                                                        <a class="btn book-ride" data-toggle="collapse"
                                                                            data-target="#collapse{!! $i !!}" aria-expanded="true"
                                                                            aria-controls="collapseOne">
                                                                            @if($row->booking_status == 'completed')
                                                                                View Details
                                                                            @elseif($row->booking_status == 'canceled')
                                                                                Cancelled 
                                                                            @endif 
                                                                            <i class="details"></i>
																		</a>
                                                                    </h5>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr id="collapse{!! $i !!}" class="collapse hide" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion" style="background:#fff">
                                                    <td colspan="5">
                                                        <div class="card-body row" style="color: black;">
                                                            <div class="col-md-7">
                                                                <div class="route">
                                                                    <div class="row">
																		<div class="col-md-6"><span>Transfer date &amp; time</span><br>
																		{!! dateFormat($row->transfer_datetime, 'M d, Y h:i A') !!}</div>

																		@if($row->is_return_way == 1)
																		<div class="col-md-6" style="text-align:right"><span>Return date
																				&amp; time</span><br>{!! dateFormat($row->return_datetime, 'M d, Y h:i A') !!}</div>
																		@endif
																	</div>
                                                                </div>
                                                                <div class="route">
																	<div class="row">
																		<div class="col-md-4"><span>From </span><br>{!! $row->from_location !!}</div>
																		<div class="col-md-4" style="text-align:center"><span>To
																			</span><br>{!! $row->to_location !!}</div>
																		<div class="col-md-4" style="text-align:right"><span>No. of
																				Passengers</span><br>{!! $row->no_of_adult + $row->no_of_children !!}</div>
																	</div>
																</div>
                                                                @if($row->is_flight || $row->is_meeting || $row->is_promo_code)
																	<div class="route">
																		<div class="row">
																			@if($row->is_flight)
																			<div class="col-md-4"><span>Flight or train number</span><br>{!! $row->flight_no !!}
																			</div>
																			@endif 

																			@if($row->is_meeting)
																			<div class="col-md-4" style="text-align:center"><span>Name
																					Sign</span><br>{!! $row->passenger_name !!}</div>
																			@endif

																			@if($row->is_promo_code)
																			<div class="col-md-4" style="text-align:right"><span>Promo
																					code</span><br>{!! $row->promo_code !!}</div>
																			@endif
																		</div>
																	</div>
																@endif
                                                            </div>

                                                            <div class="col-md-5">
                                                                <iframe
                                                                    src="https://www.google.com/maps/embed?pb=!1m28!1m12!1m3!1d1773182.221176982!2d75.40930041836052!3d29.770512315577353!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m13!3e0!4m5!1s0x391a837462345a7d%3A0x681102348ec60610!2sLudhiana%2C%20Punjab!3m2!1d30.900965!2d75.8572758!4m5!1s0x390cfd3c113a7b05%3A0xf8913afee1665916!2sNew%20Delhi%20Railway%20Station%2C%20Bhavbhuti%20Marg%2C%20Ratan%20Lal%20Market%2C%20Kamla%20Market%2C%20Ajmeri%20Gate%2C%20New%20Delhi%2C%20Delhi!3m2!1d28.642891499999998!2d77.2190894!5e0!3m2!1sen!2sin!4v1594463226630!5m2!1sen!2sin"
                                                                    width="100%" height="310" frameborder="0" style="border:0;"
                                                                    allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                                            </div>

                                                            <div class="">
                                                                <div class="col-md-12">
                                                                    <h4 style="margin-bottom:10px">Chosen transport type</h4>
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
                                                                        <div class="col-md-4">
																			<h4>Toyota Innova, 2016</h4>
                                                                                <div><strong>Van</strong> <img src="{!! asset('front/images/visitors.svg') !!}" />x4 <img
                                                                                        src="{!! asset('front/images/bag.svg') !!}" />x6</div>
																		</div>
																		<div class="col-md-3">
																			<h4>Languages Known : <small>English, Hindi, Punjabi</small>
																			</h4>
																		</div>
																		<div class="col-md-3">
																			<h4>Amount : <br><small>{!! numberFormat($row->hire_amount) !!}</small></h4>
																		</div>

                                                                        <div class="col-md-2 car">
                                                                            <div class="gallery-item">
                                                                                &nbsp;
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <h4>Transaction Date : <br><small>26/07/2020</small></h4>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <h4>Transaction ID : <br><small>123456789012</small></h4>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <h4>Transaction Mode : <br><small>Google Pay</small></h4>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                @if($row->booking_status == 'completed')
                                                                    <div class="col-md-12">
                                                                        <h4 style="margin-bottom:10px">Review Your Ride (1 as lowest and 10 as
                                                                            highest)</h4>
                                                                        <div class="row choices">

                                                                            <div class="col-md-3">Driver Behavior : <br>
                                                                                <small>
                                                                                    <select class="form-control">
                                                                                        <option>1</option>
                                                                                        <option>2</option>
                                                                                        <option>3</option>
                                                                                        <option>4</option>
                                                                                        <option>5</option>
                                                                                        <option>6</option>
                                                                                        <option>7</option>
                                                                                        <option>8</option>
                                                                                        <option>9</option>
                                                                                        <option>10</option>
                                                                                    </select>
                                                                                </small>
                                                                            </div>
                                                                            <div class="col-md-3">Punctuality : <br>
                                                                                <small>
                                                                                    <select class="form-control">
                                                                                        <option>1</option>
                                                                                        <option>2</option>
                                                                                        <option>3</option>
                                                                                        <option>4</option>
                                                                                        <option>5</option>
                                                                                        <option>6</option>
                                                                                        <option>7</option>
                                                                                        <option>8</option>
                                                                                        <option>9</option>
                                                                                        <option>10</option>
                                                                                    </select>
                                                                                </small>
                                                                            </div>
                                                                            <div class="col-md-3">Driving Skills : <br>
                                                                                <small>
                                                                                    <select class="form-control">
                                                                                        <option>1</option>
                                                                                        <option>2</option>
                                                                                        <option>3</option>
                                                                                        <option>4</option>
                                                                                        <option>5</option>
                                                                                        <option>6</option>
                                                                                        <option>7</option>
                                                                                        <option>8</option>
                                                                                        <option>9</option>
                                                                                        <option>10</option>
                                                                                    </select>
                                                                                </small>
                                                                            </div>
                                                                            <div class="col-md-3">Overall Rating : <br>
                                                                                <small>
                                                                                    <select class="form-control">
                                                                                        <option>1</option>
                                                                                        <option>2</option>
                                                                                        <option>3</option>
                                                                                        <option>4</option>
                                                                                        <option>5</option>
                                                                                        <option>6</option>
                                                                                        <option>7</option>
                                                                                        <option>8</option>
                                                                                        <option>9</option>
                                                                                        <option>10</option>
                                                                                    </select>
                                                                                </small>
                                                                            </div>

                                                                            <div class="col-md-12">Any Comments : <br>
                                                                                <small>
                                                                                    <textarea placeholder="Comments"
                                                                                        class="form-control"></textarea>
                                                                                </small>
                                                                            </div>
                                                                            <p>&nbsp;</p>
                                                                            <div class="col-md-12">
                                                                                <button class="btn btn-warning" type="submit"
                                                                                    role="submit">Submit</button>
                                                                            </div>


                                                                        </div>

                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <h4 style="margin-bottom:10px">Your reviews on this ride</h4>
                                                                        <div class="row choices">

                                                                            <div class="col-md-3">Driver Behavior : 6<br>
                                                                            </div>
                                                                            <div class="col-md-3">Punctuality : 9<br>
                                                                            </div>
                                                                            <div class="col-md-3">Driving Skills : 5<br>
                                                                            </div>
                                                                            <div class="col-md-3">Overall Rating : 7<br>
                                                                            </div>

                                                                            <div class="col-md-12">Comments : ksdfg sdfkjgdsfj nfdsbufh
                                                                                dsfndfhdf vjnds bdsj<br>
                                                                            </div>


                                                                        </div>

                                                                    </div>
                                                                @endif

                                                                @if($row->booking_status == 'canceled')
                                                                <div class="col-md-12">
                                                                    <h4 style="margin-bottom:10px">Cancellation Reason : Outdated / User Cancelled / Cancellation Issue</h4>
                                                                    <div class="row choices">
                                                                        <div class="col-md-12">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-md-12">
                                                                    <h4 style="margin-bottom:10px">Refund Details</h4>
                                                                    <div class="row choices">
                                                                    
                                                                            <div class="col-md-3">
                                                                                <h4>Transaction Date : <br><small>26/07/2020</small></h4>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <h4>Transaction ID : <br><small>123456789012</small></h4>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <h4>Transaction Mode : <br><small>Google Pay</small></h4>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <h4>Amount : <br><small>₹140,000.00</small></h4>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else 
                                            <tr>
                                                <td colspan="6" class="text-center"> No Records </td>
                                            </tr>
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



    <div class="clearfix"></div>

@include('front.layout.footer')
?>