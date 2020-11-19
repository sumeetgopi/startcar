@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="col-md-12 text-right">
            <a class="btn btn-danger" target="_blank" href="{!! route('order.show', $result->id) !!}"><i class="fa fa-print"></i> Print</a>
        </div>
    </section>

    <section class="content">
        {!! Form::model($result, ['route' => ['order.update', $result->id], 'method' => 'patch', 'id' => 'ajax-submit']) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Proceed Order</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-responsive">
                                    <tbody>
                                        <tr>
                                            <td><strong>Order Number & Date</strong></td>
                                            <td>{!! $result->order_number !!}, {!! dateFormat($result->order_date, 'd-m-Y h:i A') !!}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Mode</strong></td>
                                            <td>{!! ucfirst($result->payment_mode) !!}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>{!! ucfirst($result->order_status) !!}</td>
                                        </tr>
                                        <tr>                                             
                                            <td><strong>Amount</strong></td>
                                            <td>
                                                <strong>Total Amount:</strong> <span class="__grand_amount"> {!! amount($result->grand_amount) !!}</span> <br>
                                                <strong>Discount Amount:</strong> <span class="__coupon_amount"> {!! amount($result->coupon_discount_amount) !!}</span> <br>
                                                <strong>Payable Amount(No. Of Product):</strong> <span class="__total_amount"> {!! amount($result->total_amount) !!}</span>({!! $result->total_product !!})
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Customer Info</strong></td>
                                            <td>
                                                <strong>Name:</strong> {!! ($result->name != '') ? $result->name : 'N/A' !!} <br>
                                                <strong>Mobile Number:</strong> {!! $result->mobile_number !!} <br>
                                                <strong>Address:</strong> {!! ($result->address) ? $result->address : 'N/A' !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <table class="table table-responsive table-bordered">
                                    <tbody>
                                        @php 
                                            $status = $result->order_status;
                                            $showCheck = false;
                                            if(in_array($status, ['pending', 'accepted', 'delivery'])) {
                                                $showCheck = true;
                                            }
                                        @endphp
                                        @if(isset($orderDetails) && count($orderDetails) > 0)
                                            <tr class="bg-blue">
                                                @if($showCheck)
                                                    <td width="3%">&nbsp;</td>
                                                @endif
                                                <td><strong>Sr No</strong></td>
                                                <td><strong>Product Name</strong></td>
                                                <td align="right"><strong>Price</strong></td>
                                                <td align="right"><strong>Prev Qty</strong></td>
                                                <td align="right"><strong>Qty</strong></td>
                                                <td align="right"><strong>Amount</strong></td>
                                            </tr>
                                            @php $i=1; @endphp
                                            @foreach($orderDetails as $detail)
                                                <tr @if($detail->is_selected == 1) class="bg-warning" @endif>
                                                    @if($showCheck)
                                                        <td class="text-center">
                                                            {!! Form::checkbox("product_id[$detail->id]", $detail->id, $detail->is_selected, ['class' => '__check']) !!}
                                                        </td>
                                                    @endif
                                                    <td>{!! $i++ !!}</td>
                                                    <td>{!! $detail->product_number .' - ' . $detail->product_name . ' (' . amount($detail->weight) . ' ' . $detail->unit_name . ')' !!}</td>

                                                    <td align="right">
                                                        <span class="__price">{!! amount($detail->product_price) !!}</span>
                                                        {!! Form::hidden("product_price[$detail->id]", $detail->product_price) !!}
                                                    </td>
                                                    <td align="right">
                                                        <span class="__prev_quantity">{!! $detail->previous_quantity !!}</span>
                                                    </td>
                                                    <td align="right">
                                                        @if($showCheck)
                                                            {!! Form::number("quantity[$detail->id]", $detail->quantity, ['class' => 'form-control text-right __quantity', 'style' => 'width: 100px;']) !!}
                                                        @else 
                                                            {!! $detail->quantity !!}
                                                        @endif
                                                    </td>
                                                    <td align="right">
                                                        <span class="__amount">{!! amount($detail->amount) !!}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="bg-info">
                                                <td colspan="{!! $showCheck ? 4 : 3 !!}">
                                                    <span style="font-size: 20px;">{!! ucfirst(getIndianCurrency((float) $result->total_amount)) !!}</span>
                                                </td>
                                                <td colspan="2" align="right">
                                                    Total Amount<br>
                                                    Discount Amount<br>
                                                    <strong>Payable Amount</strong>
                                                </td>
                                                <td align="right">
                                                    <span class="__grand_amount">{!! amount($result->grand_amount) !!}</span> <br>
                                                    <span class="__coupon_amount">{!! amount($result->coupon_discount_amount) !!}</span> <br>
                                                    <strong><span class="__total_amount">{!! amount($result->total_amount) !!}</span></strong> <br>
                                                </td>   
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if(!in_array($status, ['completed', 'canceled']))
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-3 col-md-offset-6">
                                    <div class="form-group">
                                        {!! Form::label('order_status') !!}
                                        {!! Form::select('order_status', editOrderStatus($result->order_status), $result->order_status, ['class' => 'form-control select2 __order_status']) !!}
                                    </div>

                                    <div class="form-group __cancel_reason_div" style="display: none;">
                                        {!! Form::select('cancel_reason', webCancelReason(), null, ['class' => 'form-control select2']) !!}
                                    </div>
                                </div>
                                

                                <div class="col-md-3">
                                    <button class="btn btn-success btn-block mt-25"> 
                                        <i class="fa fa-check"></i>
                                        Update Order Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
</div>
@endsection

@section('bottom_script')
<script>
    $('.__quantity').on('change keyup wheel', function() {
        let quantity = parseInt($(this).val());
        if(quantity <= 0) {
            $(this).val('0');
        }

        if(isNaN(quantity)) {
            quantity = 0;
        }

        let par = $(this).parent().parent();
        let prev_quantity = parseInt(par.find('.__prev_quantity').html());

        if(quantity > prev_quantity) {
            alert('You cant enter more than given previous quantity');
            $(this).val(prev_quantity);
        }

        if(quantity > 0) {
            par.find('.__check').prop('checked', true);
            par.closest('tr').addClass('bg-warning');
        }

        if(quantity <= 0) {
            par.find('.__check').prop('checked', false);
            par.closest('tr').removeClass('bg-warning');
        }
        calculate();
    });

    $('.__check').click(function() {
        calculate();
    });

    function calculate() {
        let grand_amount = total_amount = 0;
        $('.__check').each(function(i, v) {
            let check = $(this).is(':checked');
            let parent = $(this).parent().parent();
            let qty = parseInt(parent.find('.__quantity').val());
            let prev_qty = parent.find('.__prev_quantity').html(); 
            
            if(check) {
                if(isNaN(qty) || qty == 0) { 
                    qty = parseInt(prev_qty); 
                }
            }
            else {
                qty = 0;
            }
            
            parent.find('.__quantity').val(qty);

            let price = parseFloat(parent.find('.__price').html());
            let amount = parseFloat(qty * price); 

            grand_amount += amount;
            parent.find('.__amount').html(amount);
        });

        $('.__grand_amount').html(grand_amount);
        let coupon_amount = parseFloat($('.__coupon_amount').html());

        total_amount = grand_amount - coupon_amount;
        $('.__total_amount').html(total_amount);
    }

    $('.__order_status').on('change', function() {
        let os = $(this).val();
        if(os == 'canceled') {
            $('.__cancel_reason_div').show();
        }
        else {
            $('.__cancel_reason_div').hide();
        }
    });
</script>
@endsection