@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Product</h1>
    </section>

    <section class="content">
        {!! Form::open(['route' => 'product.store', 'method' => 'post', 'id' => 'ajax-submit', 'files' => 'true']) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create Product</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
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
                                    {!! Form::select('tags[]', $tags, null, ['class' => 'form-control select2_tag', 'multiple' => 'true']) !!}
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
                        </div>
                    </div>

                    <div class="box-footer">
                        <button class="btn btn-primary pull-right"> Save Details</button>
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
        $('body').on('change', '.__category', function(e) {
            var value = $(this).val();
            if(value != '') {
                let route = $(this).attr('data-route');
                let data = {parent_id: value};
                select2Change('.__sub_category', route, data);
            }
        });
    </script>
@endsection