<div class="col-lg-12 table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th width="3%" class="text-center">
                <input class="__check_all" type="checkbox">
            </th>
            <th width="5%">#</th>
            <th>Category Image</th>
            <th>{!! sorting('category_number', 'Category Number', $sortOrder) !!}</th>
            <th>{!! sorting('category_name', 'Category Name', $sortOrder) !!}</th>
            <th>{!! sorting('status', 'Status', $sortOrder) !!}</th>
            <th width="20%">Action</th>
        </tr>
        </thead>

        @if(isset($result) && count($result) > 0)
            <tbody>
                <?php
                    $i = pageIndex($result);
                    $path = env('CATEGORY_PATH');
                ?>
                @foreach($result as $key => $row)
                    <tr>
                        <td class="text-center">
                            <input name="toggle[]" type="checkbox" class="__check" value="{!! $row->id !!}">
                        </td>
                        <td>{!! $i++ !!}</td>
                        <td>{!! webImg($path, $row->category_image, 'width: 100px !important;') !!}</td>
                        <td>{!! $row->category_number !!}</td>
                        <td><a href="{!! route('category.edit', $row->id) !!}">{!! $row->category_name !!}</a></td>
                        <td>{!! statusSlider('category.status', $row->id, $row->status) !!}</td>
                        <td>
                            <a class="btn btn-primary btn-xs" href="{!! route('category.edit', $row->id) !!}"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-xs btn-danger __drop" href="javascript:void(0);" data-url="{!! route('category.destroy', $row->id) !!}"><i class="fa fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="8">
                        <button type="button" class="btn btn-success btn-xs __toggle_all" data-route="{!! route('category.toggle-all-status', 1) !!}"><i class="fa fa-check"></i> Active</button>
                        <button type="button" class="btn btn-danger btn-xs __toggle_all" data-route="{!! route('category.toggle-all-status', 0) !!}"><i class="fa fa-times"></i> Inactive</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="8">
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
                    <td colspan="8">
                        <div class="text-center">No Records</div>
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</div>