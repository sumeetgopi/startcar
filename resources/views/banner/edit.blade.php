@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Banner</h1>
    </section>

    <section class="content">
        {!! Form::model($result, ['route' => ['banner.update', $result->id], 'method' => 'patch', 'id' => 'ajax-submit']) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Banner</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('image') !!}
                                    {!! Form::file('image', ['class' => 'form-control']) !!} <br>
                                    {!! webImg(env('BANNER_PATH'), $result->image, 'width: 200px !important') !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('status') !!}
                                    {!! Form::select('status', status(), null, ['class' => 'form-control select2']) !!}
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