<div class="col-lg-12 table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr class="bg-blue-gradient">
                <th width="5%">#</th>
                <th>Name</th>
                <th>Mobile Number</th>
                <th>Total Orders</th>
            </tr>
        </thead>
        @if(isset($result) && count($result) > 0)
            <tbody>
                <?php $i = 1; ?>
                @foreach($result as $key => $row)
                    <tr>
                        <td>{!! $i++ !!}</td>
                        <td>{!! $row->name !!}</td>
                        <td>{!! $row->mobile_number !!}</td>
                        <td>
                            <a target="_blank" href="{!! route('order.pdf', [
                                'from_date' => $inputs['from_date'],
                                'to_date' => $inputs['to_date'],
                                'customer_id' => $row->customer_id,
                            ]) !!}">{!! $row->total_orders !!}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @else
            <tbody>
                <tr>
                    <td colspan="6" class="text-center">
                        <i class="fa fa-search text-danger"></i> Use filter to get report
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</div>