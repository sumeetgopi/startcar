@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Sub Category</h1>
    </section>

    <section class="content">
        {!! Form::model($result, ['route' => ['subcategory.update', $result->id], 'method' => 'patch', 'id' => 'ajax-submit']) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Sub Category</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('parent_category_id', 'Parent Category') !!}
                                    {!! Form::select('parent_category_id', $parentCategory, $result->parent_id, ['class' => 'form-control select2']) !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('sub_category_name') !!}
                                    {!! Form::text('sub_category_name', $result->category_name, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('status') !!}
                                    {!! Form::select('status', status(), $result->status, ['class' => 'form-control select2']) !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('subcategory_image') !!}
                                    {!! Form::file('subcategory_image', ['class' => 'form-control']) !!}
                                    {!! webImg(env('CATEGORY_PATH'), $result->category_image, 'width: 100px !important') !!}
                                </div>
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