@extends('layout.master')

@section('content')
<div class="content-wrapper">
     

    <section class="content">
        {!! Form::open(['route' => 'demo.set-data', 'method' => 'post', 'id' => 'ajax-submit', 'files' => 'true']) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Please Fill Data</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-responsive table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>
                                                {!! Form::textarea('data', null, ['class' => 'form-control', 'size' => '5x20']) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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