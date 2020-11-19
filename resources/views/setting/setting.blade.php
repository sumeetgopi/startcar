@extends('layout.master')

@section('content')
<div class="content-wrapper">
     

    <section class="content">
        {!! Form::open(['route' => 'setting.set', 'method' => 'post', 'id' => 'ajax-submit', 'files' => 'true']) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">General Setting</h3>
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
                                            <td><strong>Center Image</strong></td>
                                            <td>
                                                {!! Form::file('center_image', ['class' => 'form-control']) !!} <br>
                                                {!! webImg(env('SETTING_PATH'), getSetting('center_image'), 'width: 300px !important') !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Privacy Policy</strong></td>
                                            <td>
                                                {!! Form::textarea('policy', getSetting('policy'), ['class' => 'form-control', 'id' => 'policy']) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>About Us</strong></td>
                                            <td>
                                                {!! Form::textarea('about_us', getSetting('about_us'), ['class' => 'form-control', 'id' => 'about_us']) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Term & Condition</strong></td>
                                            <td>
                                                {!! Form::textarea('term_condition', getSetting('term_condition'), ['class' => 'form-control', 'id' => 'term_condition']) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Read Policy</strong></td>
                                            <td>
                                                {!! Form::textarea('read_policy', getSetting('read_policy'), ['class' => 'form-control', 'id' => 'read_policy']) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Customer Support</strong></td>
                                            <td>
                                                {!! Form::textarea('customer_support', getSetting('customer_support'), ['class' => 'form-control', 'id' => 'customer_support']) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Maximum Cashback Amount</strong></td>
                                            <td>
                                                {!! Form::text('maximum_cashback_amount', amount(getSetting('maximum_cashback_amount')), ['class' => 'form-control']) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Minimum Order Amount For Cashback</strong></td>
                                            <td>
                                                {!! Form::text('minimum_order_amount_for_cashback', amount(getSetting('minimum_order_amount_for_cashback')), ['class' => 'form-control']) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Invoice Address</strong></td>
                                            <td>
                                                {!! Form::textarea('invoice_address', getSetting('invoice_address'), ['class' => 'form-control', 'id' => 'invoice_address']) !!}
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

@section('bottom_js_script')
    <script src="{!! asset('asset/ckeditor/ckeditor.js') !!}"></script>
    <script>
        CKEDITOR.replace('policy');
        CKEDITOR.replace('about_us');
        CKEDITOR.replace('term_condition');
        CKEDITOR.replace('read_policy');
        CKEDITOR.replace('customer_support');
        CKEDITOR.replace('invoice_address');
    </script>
@endsection