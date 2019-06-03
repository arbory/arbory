<!DOCTYPE html>
<html>
<head>
    <title>{{ config('arbory.title', 'Arbory') }}</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link href="{{ mix('css/application.css', 'arbory') }}" media="all" rel="stylesheet"/>
    <link href="{{ mix('css/controllers/sessions.css', 'arbory') }}" media="all" rel="stylesheet"/>
</head>
<body class="controller-arbory-sessions view-edit">
    @if($errors->count())
        <div class="notifications">
            @if($errors->has('user.email'))
                @foreach($errors->get('user.email') as $error)
                    <div class="notification" data-type="error">{{ $error }}</div>
                @endforeach
            @endif

            @if($errors->has('user.password'))
                @foreach($errors->get('user.password') as $error)
                    <div class="notification" data-type="error">{{ $error }}</div>
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

    <script src="{{ mix('js/application.js', 'arbory') }}"></script>
</body>
</html>
