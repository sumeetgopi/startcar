@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Settings</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>Center Image</td>
                                                <td>
                                                    {!! Form::file('center_image', ['class' => 'form-control']) !!}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Cashback Max Order Amount</td>
                                                <td>
                                                    {!! Form::text('cashback_max_order_amount', null, ['class' => 'form-control']) !!}
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
        </section>
    </div>
@endsection