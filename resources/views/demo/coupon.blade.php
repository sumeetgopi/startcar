r@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Coupon</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create Coupon</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('coupon_name') !!}
                                        {!! Form::text('coupon_name', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('coupon_code') !!}
                                        {!! Form::text('coupon_code', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('description') !!}
                                        {!! Form::textarea('description', null, ['class' => 'form-control', 'size' => '3x4']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('start_date') !!}
                                        {!! Form::text('start_date', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('end_date') !!}
                                        {!! Form::text('end_date', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('status') !!}
                                        {!! Form::select('status', status(), 1, ['class' => 'form-control select2']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('discount_type') !!} <br>
                                        {!! radio('discount_type', discountType(), 'fixed', 'class="__discount_type"') !!}
                                    </div>
                                </div>

                                <div class="col-md-8 __coupon_div">
                                    <div class="form-group">
                                        {!! Form::label('coupon_price') !!}
                                        {!! Form::text('coupon_price', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row __percent_div" style="display: none;">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Order Amount Upto</label> {!! Form::text('order_amount_upto', null, ['class' => 'form-control', 'style' => 'width: 100px; display: inline;', 'placeholder' => '500'])  !!}
                                        &nbsp;&nbsp; <label>Discount</label> {!! Form::text('order_amount_upto_discount', null, ['class' => 'form-control', 'style' => 'width: 100px; display: inline;', 'placeholder' => '10']) !!} <label>%</label>
                                    </div>

                                    <div class="form-group">
                                        <label>Order Amount More Than</label> {!! Form::text('order_amount_more_than', null, ['class' => 'form-control', 'style' => 'width: 100px; display: inline;', 'placeholder' => '500'])  !!}
                                        &nbsp;&nbsp; <label>Discount</label> {!! Form::text('order_amount_more_than_discount', null, ['class' => 'form-control', 'style' => 'width: 100px; display: inline;', 'placeholder' => '100']) !!} <label>Fixed</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button class="btn btn-primary pull-right"> Save Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('bottom_script')
    <script>
        $('.__discount_type').change(function() {
            let v = $(this).val();
            if(v == 'percent') {
                $('.__coupon_div').hide();
                $('.__percent_div').show();
            }
            else {
                $('.__coupon_div').show();
                $('.__percent_div').hide();
            }
        });
    </script>
@endsection