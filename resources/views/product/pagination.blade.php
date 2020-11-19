<div class="col-lg-12 table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th width="3%" class="text-center">
                <input class="__check_all" type="checkbox">
            </th>
            <th width="5%">#</th>
            <th>Product Image</th>
            <th>{!! sorting('category_id', 'Category Name', $sortOrder) !!}</th>
            <th>{!! sorting('sub_category_id', 'Sub Category Name', $sortOrder) !!}</th>
            <th>{!! sorting('product_number', 'Product Number', $sortOrder) !!}</th>
            <th>{!! sorting('product_name', 'Product Name', $sortOrder) !!}</th>
            <th>{!! sorting('mrp_price', 'MRP Price', $sortOrder) !!}</th>
            <th>{!! sorting('our_price', 'Our Price', $sortOrder) !!}</th>
            <th>{!! sorting('weight', 'Weight/Unit', $sortOrder) !!}</th>
            <th>{!! sorting('status', 'Status', $sortOrder) !!}</th>
            <th width="20%">Action</th>
        </tr>
        </thead>

        @if(isset($result) && count($result) > 0)
            <tbody>
                <?php
                    $i = pageIndex($result);
                    $pi = (new \App\ProductImage);
                    $path = env('PRODUCT_PATH');
                ?>
                @foreach($result as $key => $row)
                    <tr>
                        <td class="text-center">
                            <input name="toggle[]" type="checkbox" class="__check" value="{!! $row->id !!}">
                        </td>
                        <td>{!! $i++ !!}</td>
                        <td>
                            <?php
                                $image = $pi->getImage($row->id);
                                echo webImg($path, $image, 'width: 100px !important;')
                            ?>
                        </td>
                        <td>{!! $row->p_category_name !!}</td>
                        <td>{!! $row->s_category_name ?? 'N/A' !!}</td>
                        <td>{!! $row->product_number !!}</td>
                        <td><a href="{!! route('product.edit', $row->id) !!}">{!! $row->product_name !!}</a></td>
                        <td>{!! $row->mrp_price !!}</td>
                        <td>{!! $row->our_price !!}</td>
                        <td>{!! (float) $row->weight . '/' . $row->unit_name !!}</td>
                        <td>{!! statusSlider('product.status', $row->id, $row->status) !!}</td>
                        <td>
                            <a class="btn btn-primary btn-xs" href="{!! route('product.edit', $row->id) !!}"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-xs btn-danger __drop" href="javascript:void(0);" data-url="{!! route('product.destroy', $row->id) !!}"><i class="fa fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="12">
                        <button type="button" class="btn btn-success btn-xs __toggle_all" data-route="{!! route('product.toggle-all-status', 1) !!}"><i class="fa fa-check"></i> Active</button>
                        <button type="button" class="btn btn-danger btn-xs __toggle_all" data-route="{!! route('product.toggle-all-status', 0) !!}"><i class="fa fa-times"></i> Inactive</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="12">
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
                    <td colspan="12">
                        <div class="text-center">No Records</div>
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</div>