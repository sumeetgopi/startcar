@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h2 class="mt-0">State</h2>
            </div>
            <div class="col-md-6 text-right">
                <a href="{!! route('state.create') !!}" class="btn btn-success">
                    Create State
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="box-title">List State</h3>
                            </div>

                          
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::open(['route' => 'state.index', 'method' => 'get', 'id' => 'form-search']) !!}
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group drop-icon-list">
                                                        {!! Form::label('status') !!}
                                                        {!! Form::select('status', status(), null, ['class' => 'form-control select2']) !!}
                                                    </div>
                                                </div>
        
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-primary mt-25"><i class="fa fa-search"></i></button>
                                                    <button class="btn btn-default mt-25" type="button" onclick="window.location.href = '{!! route('brand.index') !!}'">
                                                        <i class="fa fa-refresh"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        {!! Form::label('search') !!}
                                                        <input class="form-control" name="keyword" placeholder="Search" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {!! Form::label('per_page') !!}
                                                        {!! Form::select('perPage', perPage(), null, ['class' => 'form-control select2', 'id' => 'perPage']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
        
                                <div class="row" id="pagination" data-url="{!! route('state.index') !!}">
                                    @include('state.pagination')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection