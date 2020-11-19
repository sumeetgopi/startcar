<div class="col-lg-12 table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th width="3%" class="text-center">
                <input class="__check_all" type="checkbox">
            </th>
            <th width="5%">#</th>
            <th>{!! sorting('sr_number', 'Sr Number', $sortOrder) !!}</th>
            <th>{!! sorting('code', 'Code', $sortOrder) !!}</th>
            <th>{!! sorting('name', 'Name', $sortOrder) !!}</th>
            <th>{!! sorting('description', 'Description', $sortOrder) !!}</th>
            <th>{!! sorting('expiry_date', 'Expiry Date', $sortOrder) !!}</th>
            <th>{!! sorting('apply_type', 'Apply Type', $sortOrder) !!}</th>
            <th>Coupon Info</th>
            <th>{!! sorting('status', 'Status', $sortOrder) !!}</th>
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
                        <td class="text-center">
                            <input name="toggle[]" type="checkbox" class="__check" value="{!! $row->id !!}">
                        </td>
                        <td>{!! $i++ !!}</td>
                        <td>{!! $row->sr_number !!}</td>
                        <td><a href="{!! route('coupon.edit', $row->id) !!}">{!! $row->code !!}</a></td>
                        <td>{!! $row->name !!}</td>
                        <td>{!! $row->description !!}</td>
                        <td>{!! dateFormat($row->expiry_date, 'd-m-Y') !!}</td>
                        <td>{!! $row->apply_type !!}</td>
                        <td>
                            <strong>Type: </strong> {!! $row->coupon_type !!} <br>
                            @if($row->coupon_type == 'cashback')
                                <strong>Cashback Amount: </strong> {!! amount($row->cb_amount) !!} 
                            @elseif($row->coupon_type == 'coupon')
                                <strong>Discount Type: </strong> {!! $row->c_discount_type !!} <br>
                                <strong>Order Amount Upto: </strong> {!! amount($row->c_order_amount_upto) !!} <br>
                                @if($row->c_discount_type == 'fixed')
                                    <strong>Discount Amount (Fixed): </strong> {!! amount($row->c_order_amount_upto_fix_amount) !!} 
                                @elseif($row->c_discount_type == 'percent')
                                    <strong>Discount Amount (%): </strong> {!! amount($row->c_order_amount_upto_percent) !!} <br>
                                    <strong>Order Amount More Than: </strong> {!! amount($row->c_order_amount_more_than) !!} <br>
                                    <strong>Discount Amount (Fixed): </strong> {!! amount($row->c_order_amount_more_than_fix_amount) !!} <br>
                                @endif
                            @endif
                        </td>
                        <td>{!! statusSlider('coupon.status', $row->id, $row->status) !!}</td>
                        <td>
                            <a class="btn btn-primary btn-xs" href="{!! route('coupon.edit', $row->id) !!}"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-xs btn-danger __drop" href="javascript:void(0);" data-url="{!! route('coupon.destroy', $row->id) !!}"><i class="fa fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="11">
                        <button type="button" class="btn btn-success btn-xs __toggle_all" data-route="{!! route('coupon.toggle-all-status', 1) !!}"><i class="fa fa-check"></i> Active</button>
                        <button type="button" class="btn btn-danger btn-xs __toggle_all" data-route="{!! route('coupon.toggle-all-status', 0) !!}"><i class="fa fa-times"></i> Inactive</button>
                    </td>
                </tr>
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