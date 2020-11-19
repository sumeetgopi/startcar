<div class="col-lg-12 table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr class="bg-blue-gradient">
                <th>Total Downloads</th>
                <th>Total Client Registered</th>
                <th>Total Orders</th>
                <th>Total Orders Amount</th>
            </tr>
        </thead>
        @if($result)
            <tbody>
                <tr>
                    <td>{!! $totalDownloads !!}</td>
                    <td>{!! $totalClientRegistered !!}</td>
                    <td>
                        <a target="_blank" href="{!! route('order.pdf', [
                            'from_date' => $inputs['from_date'],
                            'to_date' => $inputs['to_date'],
                        ]) !!}">{!! $totalOrders !!}</a>
                    </td>
                    <td>
                        <a target="_blank" href="{!! route('order.pdf', [
                            'from_date' => $inputs['from_date'],
                            'to_date' => $inputs['to_date'],
                        ]) !!}">{!! amount($totalOrderAmount) !!}</a>
                    </td>
                </tr>
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