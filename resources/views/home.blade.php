@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Dashboard</h1>

            {{--<ol class="breadcrumb">
                <li><a ><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>--}}
        </section>
        <!-- Main content -->
        <div class="content">
            <!-- Info boxes -->
            <div class="row">
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3>1000</h3>
                            <p>Total App Downloads</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-people-outline"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>2000</h3>
                            <p>Total Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-money"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>3000</h3>
                            <p>Delivered Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-money"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>4000</h3>
                            <p>Pending Orders</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-money"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css_script')
    <link rel="stylesheet" href="{!! asset('asset/jvectormap/jquery-jvectormap.css') !!}" />
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADlk166150RMLLGby78Ayq9kUKyAdHtp0&libraries=visualization"></script>
@endsection