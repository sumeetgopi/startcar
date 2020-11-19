<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Jeetti CRM</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{!! asset('asset/bootstrap/dist/css/bootstrap.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('asset/font-awesome/css/font-awesome.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('asset/Ionicons/css/ionicons.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('asset/dist/css/AdminLTE.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('asset/plugins/iCheck/square/blue.css') !!}">
    <link rel="stylesheet" href="{!! asset('asset/custom/loading.css') !!}">
    <link rel="stylesheet" href="{!! asset('asset/custom/custom.css') !!}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <b>JEETTI</b> CRM
    </div>

    <div class="login-box-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group has-feedback">
                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email') }}" autofocus>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @error('email')
                    <span class="invalid-feedback error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @error('password')
                    <span class="invalid-feedback error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-danger btn-block btn-flat">Login</button>
                </div>
            </div>
        </form>

        <div class="row" style="margin-top: 10px;">
            <div class="col-md-12">
                <a href="#">I forgot my password</a><br>
            </div>
        </div>
    </div>
</div>
</body>
</html>