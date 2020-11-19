@extends('layout.pdf')

@section('content')
<table border="1" width="100%">
    <thead>
    <tr>
        <th align="center">Sr No</th>
        <th align="center">Order Number & Date</th>
        <th align="center">Name</th>
        <th align="center">Mobile Number</th>
        <th align="center">Payment Mode</th>
        <th align="center">Order Status</th>
        <th align="center">Amount(No. Of Products)</th>
    </tr>
    </thead>

    @if(isset($result) && count($result) > 0)
        <tbody>
            @php $i=1; @endphp
            @foreach($result as $key => $row)
                <tr>
                    <td align="center">{!! $i++ !!}</td>
                    <td align="center">{!! $row->order_number .', '. date('d-m-Y h:i A', strtotime($row->order_date)) !!}</td>
                    <td align="center">{!! ucwords($row->name) !!}</td>
                    <td align="center">{!! $row->mobile_number !!}</td>
                    <td align="center">{!! ucwords($row->payment_mode) !!}</td>
                    <td align="center">{!! ucwords($row->order_status) !!}</td>
                    <td align="center">
                        <strong>Total Amount:</strong> {!! amount($row->grand_amount) !!}</span> <br>
                        <strong>Discount Amount:</strong> {!! amount($row->coupon_discount_amount) !!}</span> <br>
                        <strong>Payable Amout:</strong> {!! amount($row->total_amount) !!}({!! $row->total_product !!})
                    </td>
                </tr>
            @endforeach
        </tbody>
    @else
        <tbody>
            <tr>
                <td colspan="7" align="center">No Records</td>
            </tr>
        </tbody>
    @endif
</table> 
@endsection