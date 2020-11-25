@include('front.layout.header2')



        <!-- Pagewrap Start Here -->
<div class="content">
    <!--  Banner Start here -->
    <section class="book-ride-banner inner-banner">
        <div class="banner-box">
            <h1><span>Forgot Password </span></h1>

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
                                {!! Form::open(['route' => 'front.forgot-password.post', 'method' => 'post', 'id' => 'ajax-submit', 'files' => 'true']) !!}
                                <div class="alert alert-danger 207_error" style="display: none;"></div>
                                    <div class="form-group">
                                        <input type="text" name="email" value="" class="form-control" placeholder="Email">
                                    </div>
                                    <button type="submit" class="btn book-ride submit">Submit</button>
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