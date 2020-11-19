@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Cashback/Coupon</h1>
    </section>

    <section class="content">
        {!! Form::model($result, ['route' => ['coupon.update', $result->id], 'method' => 'patch', 'id' => 'ajax-submit']) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit #{!! ucfirst($result->coupon_type) !!}</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            {!! Form::hidden('coupon_type', $result->coupon_type) !!}
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
                                    {!! Form::select('status', status(), $result->status, ['class' => 'form-control select2']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('expiry_date') !!}
                                    {!! Form::text('expiry_date', dateFormat($result->expiry_date, 'd-m-Y'), ['class' => 'form-control datepicker', 'readonly' => 'readonly']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('apply_type') !!} <br>
                                    {!! radio('apply_type', applyType(), $result->apply_type) !!}
                                </div>
                            </div>

                            @if($result->coupon_type == 'cashback')
                                <div class="col-md-6 __cashback_div">
                                    <div class="form-group">
                                        {!! Form::label('cb_amount', 'Cashback Amount (Fixed)') !!} <br>
                                        {!! Form::text('cb_amount', amount($result->cb_amount), ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            @elseif($result->coupon_type == 'coupon')
                                <div class="col-md-12">
                                    <div class="row __coupon_div">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('c_discount_type', 'Discount Type') !!} <br>
                                                {!! radio('c_discount_type', discountType(), $result->c_discount_type, 'class="__discount_type"') !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('c_order_amount_upto', 'Order Amount Upto') !!}
                                                {!! Form::text('c_order_amount_upto', amount($result->c_order_amount_upto), ['class' => 'form-control']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 __coupon_fixed_div" @if($result->c_discount_type == 'percent') style="display:none" @endif>
                                            <div class="form-group">
                                                {!! Form::label('c_order_amount_upto_fix_amount', 'Discount Amount (Fixed)') !!}
                                                {!! Form::text('c_order_amount_upto_fix_amount', amount($result->c_order_amount_upto_fix_amount), ['class' => 'form-control']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6 __coupon_percent_div" @if($result->c_discount_type == 'fixed') style="display:none" @endif>
                                            <div class="form-group">
                                                {!! Form::label('c_order_amount_upto_percent', 'Discount Amount (%)') !!}
                                                {!! Form::text('c_order_amount_upto_percent', amount($result->c_order_amount_upto_percent), ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row __coupon_percent_div" @if($result->c_discount_type == 'fixed') style="display:none" @endif>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('c_order_amount_more_than', 'Order Amount More Than') !!}
                                                {!! Form::text('c_order_amount_more_than', amount($result->c_order_amount_more_than), ['class' => 'form-control']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('c_order_amount_more_than_fix_amount', 'Discount Amount (Fixed)') !!}
                                                {!! Form::text('c_order_amount_more_than_fix_amount', amount($result->c_order_amount_more_than_fix_amount), ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="box-footer">
                        <button class="btn btn-primary"> Save Details</button>
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