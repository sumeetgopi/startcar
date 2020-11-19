<div class="col-lg-12 table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr class="bg-blue-gradient">
                <th width="5%">#</th>
                <th>Name</th>
                <th>Mobile Number</th>
                <th>Last Login</th>
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
                        <td>{!! friendlyTime($row->updated_at) .' (' . dateFormat($row->updated_at, 'd-M-Y h:i A').')' !!}</td>
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