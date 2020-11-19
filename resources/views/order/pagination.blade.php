<div class="col-lg-12 table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            @if(in_array($inputs['order_status'], ['accepted', 'delivery']))
                <th width="3%" class="text-center">
                    <input class="__check_all" type="checkbox">
                </th>
            @endif
            <th width="5%">#</th>
            <th>Order Number & Date</th>
            <th>{!! sorting('name', 'Name', $sortOrder) !!}</th>
            <th>{!! sorting('mobile_number', 'Mobile No', $sortOrder) !!}</th>
            <th>{!! sorting('payment_mode', 'Payment Mode', $sortOrder) !!}</th>
            <th>{!! sorting('order_status', 'Order Status', $sortOrder) !!}</th>
            <th class="text-right">{!! sorting('total_amount', 'Amount(No. Of Products)', $sortOrder) !!}</th>
            <th width="20%">Action</th>
        </tr>
        </thead>

        @if(isset($result) && count($result) > 0)
            <tbody>
                <?php
                    $i = pageIndex($result);
                ?>
                @foreach($result as $key => $row)
                    <tr>
                        @if(in_array($inputs['order_status'], ['accepted', 'delivery']))
                            <td class="text-center">
                                <input name="toggle[]" type="checkbox" class="__check" value="{!! $row->id !!}">
                            </td>
                        @endif
                        <td>{!! $i++ !!}</td>
                        <td>{!! $row->order_number !!}, {!! dateFormat($row->order_date, 'd-m-Y h:i A') !!}</td>
                        <td>{!! ($row->name != '') ? ucwords($row->name) : 'N/A' !!}</td>
                        <td>{!! $row->mobile_number !!}</td>
                        <td>{!! ucfirst($row->payment_mode) !!}</td>
                        <td>{!! ucfirst($row->order_status) !!}</td>
                        <td class="text-right">
                            <strong>Total Amount:</strong> {!! amount($row->grand_amount) !!}</span> <br>
                            <strong>Discount Amount:</strong> {!! amount($row->coupon_discount_amount) !!}</span> <br>
                            <strong>Payable Amout:</strong> {!! amount($row->total_amount) !!}({!! $row->total_product !!})
                        </td>
                        <td>
                            <a class="btn btn-primary btn-xs" href="{!! route('order.edit', $row->id) !!}"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-danger btn-xs" target="_blank" href="{!! route('order.show', $row->id) !!}"><i class="fa fa-print"></i> Print</a>
                            {{-- <a class="btn btn-xs btn-danger __drop" href="javascript:void(0);" data-url="{!! route('order.destroy', $row->id) !!}"><i class="fa fa-trash"></i> Delete</a> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                @if(in_array($inputs['order_status'], ['accepted', 'delivery']))
                    <tr>
                        <td colspan="11">
                            @if($inputs['order_status'] == 'accepted')
                                <div class="form-inline">
                                    <button type="button" class="btn btn-warning btn-xs __toggle_all" data-route="{!! route('order.toggle-all-status', 'delivery') !!}"><i class="fa fa-truck"></i> Delivery</button>
                                    <div class="input-group"> 
                                        {!! Form::select('cancel_reason', webCancelReason(), null, ['class' => 'form-control input-sm cancel-select __cancel_reason']) !!}
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger btn-xs __toggle_all_cancel" data-route="{!! route('order.toggle-all-status', 'canceled') !!}" type="button"> Canceled <i class="fa fa-check"></i></button>
                                        </span>
                                    </div>
                                </div>

                            @elseif($inputs['order_status'] == 'delivery')
                                <div class="form-inline">
                                    <button type="button" class="btn btn-primary btn-xs __toggle_all" data-route="{!! route('order.toggle-all-status', 'pending') !!}"><i class="fa fa-undo"></i> Pending</button>
                                    <button type="button" class="btn btn-success btn-xs __toggle_all" data-route="{!! route('order.toggle-all-status', 'completed') !!}"><i class="fa fa-check"></i> Completed</button>
                                    <div class="input-group"> 
                                        {!! Form::select('cancel_reason', webCancelReason(), null, ['class' => 'form-control input-sm cancel-select __cancel_reason']) !!}
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger btn-xs __toggle_all_cancel" data-route="{!! route('order.toggle-all-status', 'canceled') !!}" type="button"> Canceled <i class="fa fa-check"></i></button>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="11">
                        <div class="row">
                            <div class="col-md-12">{!! $result->links() !!}</div>
                            <div class="col-md-12 text-right">{!! pageDetail($result) !!}</div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        @else
            <tbody>
                <tr>
                    <td colspan="11">
                        <div class="text-center">No Records</div>
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</div>