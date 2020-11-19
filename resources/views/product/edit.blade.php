@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Product</h1>
    </section>

    <section class="content">
        {!! Form::model($result, ['route' => ['product.update', $result->id], 'method' => 'patch', 'id' => 'ajax-submit']) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Product</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('category_id', 'Category') !!}
                                            {!! Form::select('category_id', $category, null, [
                                                'class' => 'form-control select2 __category',
                                                'data-route' => route('category.service')
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('sub_category_id', 'Sub Category') !!}
                                            {!! Form::select('sub_category_id', $subCategory, null, ['class' => 'form-control select2 __sub_category']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('unit_id', 'Unit') !!}
                                            {!! Form::select('unit_id', $unit, null, ['class' => 'form-control select2']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('product_name') !!}
                                            {!! Form::text('product_name', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('description') !!}
                                            {!! Form::textarea('description', null, ['class' => 'form-control', 'size' => '4x4']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('tags') !!} <br>
                                            {!! Form::select('tags[]', $tags, $productTags, ['class' => 'form-control select2_tag', 'multiple' => 'true']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('mrp_price') !!}
                                            {!! Form::text('mrp_price', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('our_price') !!}
                                            {!! Form::text('our_price', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('weight') !!}
                                            {!! Form::text('weight', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('status') !!}
                                            {!! Form::select('status', status(), 1, ['class' => 'form-control select2']) !!}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('product_image') !!} <small>(Multiple)</small>
                                            {!! Form::file('product_image[]', ['class' => 'form-control', 'multiple' => 'multiple']) !!}
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('category_image') !!}
                                            {!! Form::file('category_image', ['class' => 'form-control']) !!}
                                            {!! webImg(env('CATEGORY_PATH'), $result->category_image, 'width: 100px !important') !!}
                                        </div>
                                    </div> --}}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-responsive table-bordered">
                                    <tbody>
                                        @if(isset($productImages) && count($productImages) > 0) 
                                            <tr>
                                                <td><strong>Sr No</strong></td>
                                                <td><strong>Image</strong></td>
                                                <td><strong>Action</strong></td>
                                            </tr>
                                            @php $i=1; @endphp
                                            @foreach($productImages as $img) 
                                                <tr>
                                                    <td>{!! $i++ !!}</td>
                                                    <td>{!! webImg(env('PRODUCT_PATH'), $img->product_image, 'width: 150px !important') !!}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-xs __delete_img" 
                                                            data-route="{!! route('product.delete-image') !!}"
                                                            data-product-id="{!! $img->product_id !!}"
                                                            data-product-image-id="{!! $img->id !!}"
                                                            data-product-image="{!! $img->product_image !!}">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button class="btn btn-primary"> Save Details</button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
</div>
@endsection

@section('bottom_script')
    <script>
        $(document).ready(function() {
            $('.__delete_img').click(function(e) {
                e.preventDefault();
                let conff = confirm('Are you sure?');
                if(conff) {
                    let product_id = $(this).attr('data-product-id');
                    let product_image_id = $(this).attr('data-product-image-id');
                    let product_image = $(this).attr('data-product-image');
                    let route = $(this).attr('data-route');
                    let token = $('meta[name="_token"]').attr('content');
                    
                    $.ajax({
                        type: 'post',
                        url: route,
                        data: {
                            product_id: product_id,
                            product_image_id: product_image_id,
                            product_image: product_image,
                            _token: token 
                        },
                        success: function(data) {
                            if (data.success) {
                                alert(data.message);
                                window.location.reload();
                            }
                        },
                        error: function(data) {
                            alert('An error occurred.');
                        }
                    });
                }
            });
        });
    </script>
@endsection