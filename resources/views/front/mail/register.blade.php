<!DOCTYPE html>
<html lang="en-US">
<head>
   <meta charset="utf-8">
</head>
<body>
<div class="main">
   Your Login Details are: <br/><br/>
   <strong>Url:</strong> <a href="{!! $data['url'] !!}">{!! $data['url'] !!}</a> <br/>
   <strong>Username:</strong> {{ $data['username'] }} <br/>
   <strong>Password:</strong> {{ $data['password'] }} <br/>
</div>
</body>
</html>