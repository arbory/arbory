<!DOCTYPE html>
<html>
<head>
    <title>{{ config('arbory.title', 'Arbory') }}</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link rel="icon" href="/vendor/arbory/images/favicon.ico">

    @include('arbory::layout.partials.assets-css')
</head>
<body class="controller-arbory-sessions view-edit">
    @include('arbory::layout.partials.message', ['class' => 'fixed'])

    <div class="container">
        <div class="box">
            <div class="logo"></div>

            @yield('content')
        </div>
    </div>

    @include('arbory::layout.partials.assets-js')
</body>
</html>
