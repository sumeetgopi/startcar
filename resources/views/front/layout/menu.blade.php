@if(isAuthCustomerLogin())
    <div class="header-bottom">
        <div class="container">
            <div class="bottom-left col-md-12">
                <a class="navbar-brand d-md-none" href="{!! asset('front/index.php') !!}"><img src="{!! asset('front/images/logo.png') !!}" alt="Jeetii" title="Jeetii"></a>
                <nav class="navbar navbar-expand-md">



                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="col-md-2"><li><a href="{!! asset('front/index.php') !!}"><img src="{!! asset('front/images/logo.png') !!}" alt="Jeetii" title="Jeetii" width="160"></a></li></div>
                        <div class="col-md-10">
                            <ul class="navbar-nav">


                                <li class="d-none d-md-block">
                                    <a href="{!! route('front.home') !!}">Home<span class="sr-only">(current)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a href="#">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{!! route('front.home') !!}#fleet">Our Fleet</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#">Feedback</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{!! route('front.faqs') !!}">FAQs</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#">Contact Us</a>
                                </li>
                                <li class="nav-item">
                                    <a style="color: #fdb531; text-transform: none;" href="javascript:void(0);">Welcome ({!! authCustomerEmail() !!})</a>
                                </li>


                                <li class="nav-item d-md-none"><a href="{!! route('front.pending') !!}">Order History</a></li>
                                <li class="nav-item d-md-none"><a href="{!! route('front.reward') !!}">Miles Rewards</a></li>
                                <li class="nav-item d-md-none"><a href="{!! route('front.setting') !!}">Account Settings</a></li>
                                <li class="nav-item d-md-none"><a href="#">Logout</a></li>
                            </ul>


                            <ul class="navbar-nav">

                                <li><a href="{!! route('front.book') !!}">Book A Ride</a></li>
                                <li><a href="{!! route('front.pending') !!}">Order History</a></li>
                                <li><a href="{!! route('front.reward') !!}">Miles Rewards</a></li>
                                <li><a href="{!! route('front.setting') !!}">Account Settings</a></li>
                                <li>

                                    <a href="{{ route('front.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>

                        </div>
                    </div>
                </nav>
            </div>

        </div>
    </div>
@else
    <div class="header-top">
        <div class="container">
            <div class="top-left">
                <a href="{!! route('front.home') !!}"><img src="{!! asset('front/images/logo.png') !!}" alt="Jeetii" title="Jeetii" width="160"></a>
            </div>
            <div class="top-right">
                <div class="left">
                    <ul>
                        <li><a href="#">Drive with us</a></li>
                        <li><a class="poppup-forms login-form" data-toggle="modal" data-target="#login_form">Login</a></li>
                        <li><a class="poppup-forms sign-upform" data-toggle="modal" data-target="#signup_form">Register</a></li>
                    </ul>
                </div>
                <div class="right">
                    <a href="{!! route('front.book') !!}" class="btn book-ride">Book a ride</a>
                </div>
            </div>
        </div>
    </div>

    <div class="header-bottom">
        <div class="container">
            <div class="bottom-left">
                <nav class="navbar navbar-expand-md">
                    <a class="navbar-brand d-md-none" href="{!! route('front.home') !!}"><img src="{!! asset('front/images/logo.png') !!}" alt="Jeetii" title="Jeetii"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a href="{!! route('front.home') !!}">Home<span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a href="#">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="{!! route('front.home') !!}#fleet">Our Fleet</a>
                            </li>
                            <li class="nav-item">
                                <a href="#">Feedback</a>
                            </li>
                            <li class="nav-item">
                                <a href="{!! route('front.faqs') !!}">FAQs</a>
                            </li>
                            <li class="nav-item">
                                <a href="#">Contact Us</a>
                            </li>
                            <li class="nav-item d-md-none">
                                <a href="#">Drive with us</a>
                            </li>
                            <li class="nav-item d-md-none">
                                <a href="{!! route('front.book') !!}">Book a ride</a>
                            </li>
                            <li class="nav-item d-md-none">
                                <a class="poppup-forms login-form" href="#login-form">Login</a>
                            </li>
                            <li class="nav-item d-md-none">
                                <a class="poppup-forms sign-upform" href="#sign-up">Register</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="bottom-right">
                <ul>
                    <li><a href="#" target="_blank"><span><i class="fab fa-facebook-f"></i></span></a></li>
                    <!--<li><a href="#" target="_blank"><span><i class="fab fa-twitter"></i></span></a></li>
                    <li><a href="#" target="_blank"><span><i class="fab fa-pinterest-p"></i></span></a></li>-->
                    <li><a href="#" target="_blank"><span><i class="fab fa-instagram"></i></span></a></li>
                    <!--<li><a href="#" target="_blank"><span><i class="fab fa-linkedin-in"></i></span></a></li>-->
                </ul>
            </div>
        </div>
    </div>
@endif