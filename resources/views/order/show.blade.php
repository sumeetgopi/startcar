@extends('layout.print')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-responsive">
                <tbody>
                    <tr>
                        <th style="border: none;">{!! getSetting('invoice_address') !!}</th>
                        <th style="border: none; text-align: center; vertical-align: top;"><h1>INVOICE</h1></th>
                    </tr>
                </tbody>
            </table>
        </div>
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
                        <td><strong>Order Status</strong></td>
                        <td>{!! ucfirst($result->order_status) !!}</td>
                    </tr>
                    <tr>
                        <td><strong>Amount</strong></td>
                        <td>
                            <strong>Total Amount:</strong> {!! amount($result->grand_amount) !!} <br>
                            <strong>Discount Amount:</strong> {!! amount($result->coupon_discount_amount) !!} <br>
                            <strong>Payable Amount(No. Of Product):</strong> {!! amount($result->total_amount) !!}({!! $result->total_product !!})
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
                    @if(isset($orderDetails) && count($orderDetails) > 0)
                        <tr>
                            <td><strong>Sr No</strong></td>
                            <td><strong>Product Name</strong></td>
                            <td align="right"><strong>Prev Qty</strong></td>
                            <td align="right"><strong>Qty</strong></td>
                            <td align="right"><strong>Price</strong></td>
                            <td align="right"><strong>Amount</strong></td>
                        </tr>
                        @php $i=1; @endphp
                        @foreach($orderDetails as $detail)
                            <tr>
                                <td>{!! $i++ !!}</td>
                                <td>{!! $detail->product_number .' - ' . $detail->product_name . ' (' . amount($detail->weight) . ' ' . $detail->unit_name . ')' !!}</td>
                                <td align="right">{!! amount($detail->previous_quantity) !!}</td>
                                <td align="right">{!! amount($detail->quantity) !!}</td>
                                <td align="right">{!! amount($detail->product_price) !!}</td>
                                <td align="right">{!! amount($detail->amount) !!}</td>
                            </tr>
                        @endforeach

                        <tr class="bg-info">
                            <td colspan="2">
                                <span style="font-size: 20px;">{!! ucfirst(getIndianCurrency((float) $result->total_amount)) !!}</span>
                            </td>
                            <td colspan="3" align="right">
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
@endsection

@section('bottom_script')
    <script>
        $(document).ready(function() {
            window.print();
        })
    </script>
@endsection