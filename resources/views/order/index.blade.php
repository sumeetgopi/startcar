@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-0">Order</h2>
            </div>
            {{--<div class="col-md-6 text-right">
                <a href="{!! route('order.create') !!}" class="btn btn-success">
                    Create Order
                </a>
            </div>

            <div class="col-md-12 text-right">
                {!! Form::open(['route' => 'order.store', 'method' => 'post', 'id' => 'ajax-submit', 'files' => 'true']) !!}
                    <button class="btn btn-success pull-right" onclick="return confirm('Are you sure?');"> Create Order</button>
                {!! Form::close() !!}
            </div>--}}
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="box-title">List Order</h3>
                            </div>

                          
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::open(['route' => 'order.index', 'method' => 'get', 'id' => 'form-search']) !!}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                {{-- <div class="col-md-4">
                                                    <div class="form-group drop-icon-list">
                                                        {!! Form::label('status') !!}
                                                        {!! Form::select('status', status(), null, ['class' => 'form-control select2']) !!}
                                                    </div>
                                                </div> --}}

                                                <div class="col-md-4">
                                                    <div class="form-group drop-icon-list">
                                                        {!! Form::label('from_date') !!}
                                                        {!! Form::text('from_date', date('d-m-Y'), ['class' => 'form-control __from_date2', 'readonly' => 'true']) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group drop-icon-list">
                                                        {!! Form::label('to_date') !!}
                                                        {!! Form::text('to_date', date('d-m-Y'), ['class' => 'form-control __to_date2', 'readonly' => 'true']) !!}
                                                    </div>
                                                </div>
        
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-primary mt-25"><i class="fa fa-search"></i></button>
                                                    <button class="btn btn-default mt-25" type="button" onclick="window.location.href = '{!! route('order.index') !!}'">
                                                        <i class="fa fa-refresh"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group drop-icon-list">
                                                        {!! Form::label('order_status') !!}
                                                        {!! Form::select('order_status', orderStatus(), 'pending', ['class' => 'form-control select2']) !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {!! Form::label('search') !!}
                                                        <input class="form-control" name="keyword" placeholder="Search By Order No, Name, Email, Mobile" type="text">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        {!! Form::label('per_page') !!}
                                                        {!! Form::select('perPage', perPage(), null, ['class' => 'form-control select2', 'id' => 'perPage']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
        
                                <div class="row" id="pagination" data-url="{!! route('order.index') !!}">
                                    {{--@include('order.pagination')--}}
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