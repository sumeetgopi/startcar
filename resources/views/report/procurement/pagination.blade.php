<div class="col-lg-12 table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr class="bg-blue-gradient">
                <th width="5%">#</th>
                <th>Product Number</th>
                <th>Product Name</th>
                <th>MRP Price</th>
                <th>Our Price</th>
                <th>Total Quantity</th>
            </tr>
        </thead>
        @if(isset($result) && count($result) > 0)
            <tbody>
                <?php $i = 1; ?>
                @foreach($result as $key => $row)
                    <tr>
                        <td>{!! $i++ !!}</td>
                        <td>{!! $row->product_number !!}</td>
                        <td>{!! $row->product_name !!}</td>
                        <td>{!! amount($row->mrp_price) !!}</td>
                        <td>{!! amount($row->our_price) !!}</td>
                        <td>{!! amount($row->total_quantity) !!}</td>
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