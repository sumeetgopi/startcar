@extends('layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>Send Notification</h1>
    </section>

    <section class="content">
        {!! Form::open(['route' => 'notification.set', 'method' => 'post', 'id' => 'ajax-submit', 'files' => 'true']) !!}
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Send Notification</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('send_type') !!} <br>
                                    {!! radio('send_type', notificationSendType(), 'all', 'class="__send_type"') !!}
                                </div>
                            </div>

                            <div class="col-md-12 __mobile_number" style="display: none;">
                                <div class="form-group">
                                    {!! Form::label('mobile_number') !!} <br>
                                    {!! Form::select('mobile_number[]', customerFcmTokenService(), null, ['class' => 'form-control select2', 'multiple' => 'true']) !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('title') !!} <br>
                                    {!! Form::text('title', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('message') !!}
                                    {!! Form::textarea('message', null, ['class' => 'form-control __message']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button class="btn btn-success btn-block"> 
                            <i class="fa fa-check"></i> Send Notification 
                        </button>
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
        $('.__send_type').change(function() {
            let v = $(this).val();
            if(v == 'selected') {
                $('.__mobile_number').show();
            }
            else {
                $('.__mobile_number').hide();
            }
        });

        $('body').on('change', '.__notification', function(e) {
            var value = $(this).val();
            if(value != '') {
                let route = $(this).attr('data-route');
                let data = {id: value};
                ajaxFire(route, data, function(response) {
                    if(response.success) {
                        $('.__message').val(response.message);
                    }
                });
            }
        });
    </script>
@endsection