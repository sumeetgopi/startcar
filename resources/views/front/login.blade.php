@include('front.layout.header2')



        <!-- Pagewrap Start Here -->
<div class="content">
    <!--  Banner Start here -->
    <section class="book-ride-banner inner-banner">
        <div class="banner-box">
            <h1><span>Login </span></h1>

        </div>
    </section>
    <!--  Banner End here -->

    <!-- Search Section Start here -->
    <div class="col">
        <section class="search-section">
            <div class="container">
                <div class="content">
                    <div class="login-form" style="width: 320px; margin: 0 auto;">
                        <div class="top-sec">
                            <p></p>

                            <div class="form-section">
                                {!! Form::open(['route' => 'front.login-page.post', 'method' => 'post', 'id' => 'login-submit', 'files' => 'true']) !!}
                                <div class="alert alert-danger 207_error" style="display: none;"></div>
                                    <div class="form-group">
                                        <input type="text" name="email" value="{!! $email !!}" class="form-control" placeholder="Email/Phone">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" placeholder="Password">
                                    </div>
                                    <p class="frgot-pwd"><a href="{!! route('front.forgot-password') !!}">Forgot password?</a></p>
                                    <button type="submit" class="btn book-ride submit">LOGIN</button>
                                {!! Form::close() !!}
                            </div>

                            <p></p>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    </div>
    <!-- Search Section End here -->



    <div class="clearfix"></div>









    @include('front.layout.footer')