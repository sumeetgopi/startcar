@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Brand</h1>
    </section>

    <section class="content">
        {!! Form::open(['route' => 'brand.store', 'method' => 'post', 'id' => 'ajax-submit', 'files' => 'true']) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create Brand</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('brand_name') !!}
                                    {!! Form::text('brand_name', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            

                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('status') !!}
                                    {!! Form::select('status', status(), 1, ['class' => 'form-control select2']) !!}
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