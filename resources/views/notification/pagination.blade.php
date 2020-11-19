<div class="col-lg-12 table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th width="5%">#</th>
            <th>{!! sorting('created_at', 'Date & Time', $sortOrder) !!}</th>
            <th>{!! sorting('title', 'Title', $sortOrder) !!}</th>
            <th>{!! sorting('message', 'Message', $sortOrder) !!}</th>
            <th>{!! sorting('mobile_number', 'Mobile Number', $sortOrder) !!}</th>
        </tr>
        </thead>

        @if(isset($result) && count($result) > 0)
            <tbody>
                <?php $i = pageIndex($result); ?>
                @foreach($result as $key => $row)
                    <tr>
                        <td>{!! $i++ !!}</td>
                        <td>{!! dateFormat($row->created_at, 'd-m-Y h:i A') !!}</td>
                        <td>{!! $row->title !!}</td>
                        <td>{!! nl2br($row->message) !!}</td>
                        <td>{!! $row->mobile_number !!}</td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="5">
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
                    <td colspan="5">
                        <div class="text-center">No Records</div>
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</div>