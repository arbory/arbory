<!DOCTYPE html>
<html>
<head>
    <title>Arbory</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link href="{{ mix('/arbory/css/application.css') }}" media="all" rel="stylesheet"/>
    <link href="{{ mix('/arbory/css/controllers/sessions.css') }}" media="all" rel="stylesheet"/>
</head>
    <body class="controller-arbory-sessions view-edit">

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

            @yield('content')
        </div>
    </div>

    <script src="{{ mix('/arbory/js/application.js') }}"></script>
</body>
</html>
