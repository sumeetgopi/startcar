<!-- Newsletter Subscribe Section Start Here -->
<section class="email-subscribe-sec">
	<div class="container">
		<div class="row">
			<div class="col-md-5 col-lg-4">
				<div class="left-text">
					<h2>Signup for our newsletter</h2>
				</div>
			</div>
			<div class="col-md-7">
				<div class="subscribe-form">
					<form method="get" action="#">
						<div class="input-group md-form form-sm form-2 pl-0">
							<input class="form-control" type="text" placeholder="Enter Your Email">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-arrow-right"></i></span>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Newsletter Subscribe Section End Here -->

<!-- Footer Section Start here -->
<footer>
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-lg-4">
				<div class="logo-sec">
					<a href="index.php"><img src="{!! asset('front/images/logo.png') !!}" alt="Jeetii" width="140"></a>
					<p>Proin a enim malesuada, auctor mi et, eleifend lectus. Fusce vulputate arcu nec nisi ultricies facilisis. Nunc sed congue erat. Maecenas sit amet sem leo. aliquam id urna. Quisque sit amet erat aliquet</p>
					<ul class="footer-socio">
						<li><a href="#" target="_blank"><span><i class="fab fa-facebook-f"></i></span></a></li>
						<li><a href="#" target="_blank"><span><i class="fab fa-instagram"></i></span></a></li>
						<!--<li><a href="#" target="_blank"><span><i class="fab fa-twitter"></i></span></a></li>
                        <li><a href="#" target="_blank"><span><i class="fab fa-pinterest-p"></i></span></a></li>
                        <li><a href="#" target="_blank"><span><i class="fab fa-linkedin-in"></i></span></a></li>-->
					</ul>
				</div>
			</div>
			<div class="col-md-6 col-lg-4">
				<div class="services">
					<h3>Services</h3>
					<ul>
						<li><a href="{!! route('front.home') !!}">Home</a></li>
						<li><a href="#">About Us</a></li>
						<li><a href="{!! route('front.home') !!}#fleet">Our Fleet</a></li>
						<li><a href="#">Feedback</a></li>
						<li><a href="{!! route('front.faqs') !!}">FAQs</a></li>
					</ul>
					<ul>
						<li><a href="{!! route('front.book') !!}">Book A Ride</a></li>
						<li><a href="{!! route('front.pending') !!}">Order History</a></li>
						<li><a href="{!! route('front.reward') !!}">Miles Rewards</a></li>
						<li><a href="{!! route('front.setting') !!}">Account Settings</a></li>
						<li><a href="#">Contact Us</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-6 col-lg-4">
				<div class="get-in-touch">
					<h3>Get In Touch</h3>
					<p><i class="fas fa-map-marker-alt"></i>1336 Civil Lines, Ludhiana, (Pb) INDIA</p>
					<ul>
						<li><a href="tel:"><i class="fas fa-phone-alt"></i>+91 0123456789</a></li>
						<li><a href=""><i class="far fa-envelope"></i>info@web.com</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-bottom"><p>© 2020. All Rights Reserved.</p></div>
</footer>
<!-- Footer Section End Here -->


@include('front.layout.modal')

		<!-- Modal -->
<div class="modal fade" id="login_form" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="login-form">
					<div class="top-sec">
						<a href="{!! route('front.home') !!}" class="logo">
							<img src="{!! asset('front/images/logo3.png') !!}" alt="Jeetii" title="Jeetii"></a>
						<h3>LOGIN</h3>
						<p></p>



						<div class="form-section">
							{!! Form::open(['route' => 'front.login', 'method' => 'post', 'id' => 'login-submit', 'files' => 'true']) !!}
							<div class="alert alert-danger 207_error" style="display: none;"></div>
							<div class="form-group">
									<input type="text" name="email" class="form-control" placeholder="Email/Phone">
								</div>
								<div class="form-group">
									<input type="password" name="password" class="form-control" placeholder="Password">
								</div>
								<p class="frgot-pwd"><a href="#">Forgot password?</a></p>
								<button type="submit" class="btn book-ride submit">LOGIN</button>
							{!! Form::close() !!}
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
<div class="modal fade" id="signup_form" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="sign-up-form">
					<div class="top-sec">
						<a href="{!! route('front.home') !!}" class="logo"><img src="{!! asset('front/images/logo3.png') !!}"
															  alt="Jeetii" title="Jeetii"></a>
						<h3>REGISTER</h3>
						<p></p>
						<div class="form-section">
							{!! Form::open(['route' => 'front.register', 'method' => 'post', 'id' => 'register-submit', 'files' => 'true']) !!}
							<div class="alert alert-danger 207_error" style="display: none;"></div>

							<div class="form-group">
									<input type="text" name="name" class="form-control" placeholder="Full name*">
								</div>
								<div class="form-group">
									<input type="text" name="mobile_number" class="form-control" placeholder="Mobile number*">
								</div>
								<div class="form-group">
									<input type="text" name="email" class="form-control" placeholder="email*">
								</div>
								<div class="form-group">
									<input type="password" name="password" class="form-control" placeholder="Password">
								</div>
								<button type="submit" class="btn book-ride submit">REGISTER</button>
							{!! Form::close() !!}
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


<!--Wrapper Content End-->



<!-- jQuery (necessary for JavaScript plugins) -->
<script src="{!! asset('front/js/jquery.min.js') !!}"></script>
<!-- Fancybox -->
<script src="{!! asset('front/js/jquery.fancybox.min.js') !!}"></script>
<!-- Font Awesome JS -->
<script src="{!! asset('front/js/all.min.js') !!}"></script>
<!-- Custom JS -->
<script src="{!! asset('front/js/custom.js') !!}"></script>




<script>
	// tabs

	var tabLinks = document.querySelectorAll(".tablinks");
	var tabContent = document.querySelectorAll(".tabcontent");


	tabLinks.forEach(function(el) {
		el.addEventListener("click", openTabs);
	});


	function openTabs(el) {
		var btnTarget = el.currentTarget;
		var country = btnTarget.dataset.country;

		tabContent.forEach(function(el) {
			el.classList.remove("active");
		});

		tabLinks.forEach(function(el) {
			el.classList.remove("active");
		});

		document.querySelector("#" + country).classList.add("active");

		btnTarget.classList.add("active");
	}

</script>



<!-- Js Files Start -->
<script src="{!! asset('front/js3/jquery-1.12.5.min.js') !!}"></script>
<script src="{!! asset('front/js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('front/js3/owl.carousel.min.js') !!}"></script>
<script src="{!! asset('front/js3/jquery.counterup.min.js') !!}"></script>
<script src="{!! asset('front/js3/waypoints.min.js') !!}"></script>
<script src="{!! asset('front/js3/custom.js') !!}"></script>
<script src="{!! asset('asset/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') !!}"></script>
<script src="{!! asset('asset/select2/dist/js/select2.full.min.js') !!}"></script>

<script src="{!! asset('asset/plugins/timepicker/bootstrap-timepicker.min.js') !!}"></script>
<script src="{!! asset('front/custom/script.js') !!}"></script>
<!-- Js Files End -->




<script>
	$(document).ready(function(){

		$('.quantity-right-plus1').click(function(e){
			e.preventDefault();
			var quantity = parseInt($('#quantity1').val());
			$('#quantity1').val(quantity + 1);
		});

		$('.quantity-right-plus2').click(function(e){
			e.preventDefault();
			var quantity = parseInt($('#quantity2').val());
			$('#quantity2').val(quantity + 1);
		});

		$('.quantity-right-plus3').click(function(e){
			e.preventDefault();
			var quantity = parseInt($('#quantity3').val());
			$('#quantity3').val(quantity + 1);
		});

		$('.quantity-right-plus4').click(function(e){
			e.preventDefault();
			var quantity = parseInt($('#quantity4').val());
			$('#quantity4').val(quantity + 1);
		});

		$('.quantity-left-minus1').click(function(e){
			e.preventDefault();
			var quantity = parseInt($('#quantity1').val());
			if(quantity>0){
				$('#quantity1').val(quantity - 1);
			}
		});

		$('.quantity-left-minus2').click(function(e){
			e.preventDefault();
			var quantity = parseInt($('#quantity2').val());
			if(quantity>0){
				$('#quantity2').val(quantity - 1);
			}
		});

		$('.quantity-left-minus3').click(function(e){
			e.preventDefault();
			var quantity = parseInt($('#quantity3').val());
			if(quantity>0){
				$('#quantity3').val(quantity - 1);
			}
		});

		$('.quantity-left-minus4').click(function(e){
			e.preventDefault();
			var quantity = parseInt($('#quantity4').val());
			if(quantity>0){
				$('#quantity4').val(quantity - 1);
			}
		});

	});

	$('.__nav_vehicle_category a').click(function(e) {
		e.preventDefault();
		$('.__nav_vehicle_category a').each(function(i, v) {
			$(this).removeClass('active');
		});

		let v = $(this).attr('data-value');
		$(this).addClass('active');
		$('.__vc').val(v);
	});

	$('.__nav_vehicle_category2 a').click(function(e) {
		e.preventDefault();
		$('.__nav_vehicle_category2 a').each(function(i, v) {
			$(this).removeClass('active');
		});

		let v = $(this).attr('data-value');
		$(this).addClass('active');
		$('.__vc2').val(v);
	});

</script>
</body>
</html>