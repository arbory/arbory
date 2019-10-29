<!DOCTYPE html>
<html>
<head>
    <title>{{ config('arbory.title', 'Arbory') }}</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link href="{{ mix('css/application.css', 'arbory') }}" media="all" rel="stylesheet"/>
    <link href="{{ mix('css/controllers/sessions.css', 'arbory') }}" media="all" rel="stylesheet"/>
</head>
<body class="controller-arbory-sessions view-edit">
    @include('arbory::layout.partials.message', ['class' => 'fixed'])

    <div class="container">
        <div class="box">
            <div class="logo"></div>

            @yield('content')
        </div>
    </div>

    <script src="{{ mix('js/manifest.js', 'arbory') }}"></script>
    <script src="{{ mix('js/vendor.js', 'arbory') }}"></script>
    <script src="{{ mix('js/application.js', 'arbory') }}"></script>
</body>
</html>
