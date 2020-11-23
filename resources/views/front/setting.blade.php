@include('front.layout.header2')

<!-- Pagewrap Start Here -->
<div class="content">
    <!--  Banner Start here -->
    <section class="book-ride-banner inner-banner">
        <div class="banner-box">
            <h1><span>account </span>settings</h1>

        </div>
    </section>
    <!--  Banner End here -->

    <!-- Search Section Start here -->
    <div class="col">
        <section class="search-section">
            <div class="container">
                <div class="content row">

                    <div class="col-md-2">&nbsp;</div>
                    <div class="col-md-8 linee">
                        <form class="" action="">

                            <div class="col-md-12">
                                <div>&nbsp;</div>
                                <div class="row from-group">
                                    <div class="col-md-4 rightt"><strong>Full Name : </strong>
                                    </div>
                                    <div class="col-md-8"><input type="text" value="{!! authCustomerName() !!}" class="form-control">
                                    </div>
                                </div>
                                <div class="row from-group">
                                    <div class="col-md-4 rightt"><strong>Email : </strong>
                                    </div>
                                    <div class="col-md-8"><input type="text" class="form-control"
                                            placeholder="Email" value="{!! authCustomerEmail() !!}">
                                    </div>
                                </div>
                                <div class="row from-group">
                                    <div class="col-md-4 rightt"><strong>Phone Number : </strong>
                                    </div>
                                    <div class="col-md-8"><input type="text" class="form-control"
                                            placeholder="Phone Number" value="{!! authCustomerMobile() !!}">
                                    </div>
                                </div>
                                <div class="row from-group">
                                    <div class="col-md-4 rightt"><strong>Invitation Code : </strong>
                                    </div>
                                    <div class="col-md-8"><input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="row from-group">
                                    <div class="col-md-4 rightt"><strong>New Password : </strong>
                                    </div>
                                    <div class="col-md-8"><input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="row from-group">
                                    <div class="col-md-4 rightt"><strong>Confirm Password : </strong>
                                    </div>
                                    <div class="col-md-8"><input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="row from-group">
                                    <div class="col-md-4 rightt"><strong>Passenger Notifications : </strong>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="left">
                                            <input type="checkbox" class="form-group left" value="">
                                            Receive notifications about new offers
                                        </label>
                                        <label class="left">
                                            <input type="checkbox" class="form-group" value="">
                                            Receive news and special offers
                                        </label>
                                    </div>
                                </div>
                                <div class="row from-group">
                                    <div class="col-md-4 rightt"><strong></strong>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="left">
                                            <input type="checkbox" class="form-group left" value="checked">
                                            <strong>I have read and accepted the terms of use</strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="row from-group">
                                    <div class="col-md-4 rightt"><strong></strong>
                                    </div>
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-success btn-sm rightt">Save
                                            Changes</button><br>
                                        <button type="submit" class="btn btn-danger btn-sm rightt">Delete My
                                            Account</button>
                                    </div>
                                </div>


                            </div>

                    </div>

                    </form>
                </div>
                <div class="col-md-2">&nbsp;</div>

            </div>
    </div>
    </section>
</div>
<!-- Search Section End here -->



<div class="clearfix"></div>



@include('front.layout.footer')