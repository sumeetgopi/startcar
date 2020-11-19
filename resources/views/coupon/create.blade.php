@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Cashback/Coupon</h1>
    </section>

    <section class="content">
        {!! Form::open(['route' => 'coupon.store', 'method' => 'post', 'id' => 'ajax-submit', 'files' => 'true']) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create Cashback/Coupon</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('coupon_type') !!} <br>
                                    {!! radio('coupon_type', couponType(), 'coupon', 'class="__coupon_type"') !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('name') !!}
                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('code') !!}
                                    {!! Form::text('code', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('description') !!}
                                    {!! Form::textarea('description', null, ['class' => 'form-control', 'size' => '2x3']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('status') !!}
                                    {!! Form::select('status', status(), 1, ['class' => 'form-control select2']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('expiry_date') !!}
                                    {!! Form::text('expiry_date', date('d-m-Y'), ['class' => 'form-control datepicker', 'readonly' => 'readonly']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('apply_type') !!} <br>
                                    {!! radio('apply_type', applyType(), 'single') !!}
                                </div>
                            </div>

                            <div class="col-md-6 __cashback_div">
                                <div class="form-group">
                                    {!! Form::label('cb_amount', 'Cashback Amount (Fixed)') !!} <br>
                                    {!! Form::text('cb_amount', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row __coupon_div" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('c_discount_type', 'Discount Type') !!} <br>
                                    {!! radio('c_discount_type', discountType(), 'fixed', 'class="__discount_type"') !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('c_order_amount_upto', 'Order Amount Upto') !!}
                                    {!! Form::text('c_order_amount_upto', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-6 __coupon_fixed_div">
                                <div class="form-group">
                                    {!! Form::label('c_order_amount_upto_fix_amount', 'Discount Amount (Fixed)') !!}
                                    {!! Form::text('c_order_amount_upto_fix_amount', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-6 __coupon_percent_div" style="display: none;">
                                <div class="form-group">
                                    {!! Form::label('c_order_amount_upto_percent', 'Discount Amount (%)') !!}
                                    {!! Form::text('c_order_amount_upto_percent', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row __coupon_percent_div" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('c_order_amount_more_than', 'Order Amount More Than') !!}
                                    {!! Form::text('c_order_amount_more_than', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('c_order_amount_more_than_fix_amount', 'Discount Amount (Fixed)') !!}
                                    {!! Form::text('c_order_amount_more_than_fix_amount', null, ['class' => 'form-control']) !!}
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
        {!! Form::close() !!}

    </section>
</div>
@endsection

@section('bottom_script')
    <script>
        $('.__coupon_div').show();
        $('.__coupon_fixed_div').show();
        $('.__cashback_div').hide();
        
        $('.__coupon_type').change(function() {
            $("input[name=discount_type][value=fixed]").prop('checked', true);
            let v = $(this).val();
            if(v == 'cashback') {
                $('.__coupon_div').hide();
                $('.__coupon_percent_div').hide();
                $('.__cashback_div').show();
            }
            else {
                $('.__coupon_div').show();
                $('.__coupon_fixed_div').show();
                $('.__cashback_div').hide();
            }
        });


        $('.__discount_type').change(function() {
            let v = $(this).val();
            if(v == 'percent') {
                $('.__coupon_fixed_div').hide();
                $('.__coupon_percent_div').show();
            }
            else {
                $('.__coupon_fixed_div').show();
                $('.__coupon_percent_div').hide();
            }
        });
    </script>
@endsection