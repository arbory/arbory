<!DOCTYPE html>
<html>
<head>
    <title>Leaf</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link href="/leaf/css/application.css" media="all" rel="stylesheet"/>
    <link href="/leaf/css/controllers/sessions.css" media="all" rel="stylesheet"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body class="controller-leaf-sessions view-edit">

@if($errors->count())
    <div class="notifications">
        @if($errors->has('user.email'))
            @foreach($errors->get('user.email') as $error)
                <div class="notification" data-type="error">{{$error}}</div>
            @endforeach
        @endif
        @if($errors->has('user.password'))
            @foreach($errors->get('user.password') as $error)
                <div class="notification" data-type="error">{{$error}}</div>
            @endforeach
        @endif
    </div>
@endif

<div class="container">

    <div class="box">
        <div class="logo"></div>

        <form class="login" action="{{route('admin.login.attempt')}}" accept-charset="UTF-8" method="post">
            {!!csrf_field()!!}
            <div class="field @if($errors->has('user.email'))has-error @endif">
                <label for="email">Email</label>
                <input autofocus="autofocus" id="email" class="text" type="email"
                       value="{{$input->old('user.email')}}" name="user[email]">
            </div>
            <div class="field @if($errors->has('user.password'))has-error @endif">
                <label for="password">Password</label>
                <input id="password" class="text" type="password" name="user[password]">
            </div>
            <div class="field">
                <label>
                    <input type="checkbox" name="remember" value="1" {{$input->old('remember') ? 'checked' : ''}} />
                    Remember
                </label>
            </div>
            <div class="field">
                <button class="button" type="submit">Sign in</button>
            </div>
        </form>
    </div>
</div>
<script src="/leaf/js/application.js"></script>
</body>
</html>
