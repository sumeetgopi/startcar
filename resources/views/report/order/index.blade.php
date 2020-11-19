@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Repeat Order Report</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::open(['route' => 'report.order', 'method' => 'get', 'id' => 'form-search']) !!}
                                <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group drop-icon-list">
                                                        {!! Form::label('from_date') !!}
                                                        {!! Form::text('from_date', null, ['class' => 'form-control datepicker', 'readonly' => 'true']) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group drop-icon-list">
                                                        {!! Form::label('to_date') !!}
                                                        {!! Form::text('to_date', null, ['class' => 'form-control datepicker', 'readonly' => 'true']) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group drop-icon-list">
                                                        {!! Form::label('order_type') !!}
                                                        {!! Form::select('order_count', orderCount(), null, ['class' => 'form-control select2']) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {!! Form::label('search') !!}
                                                        <input class="form-control" name="keyword" placeholder="Search" type="text">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-primary mt-25"><i class="fa fa-search"></i></button>
                                                    <button class="btn btn-default mt-25" type="button" onclick="window.location.href = '{!! route('report.order') !!}'">
                                                        <i class="fa fa-refresh"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
        
                                <div class="row" id="pagination">
                                    @include('report.order.pagination')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection