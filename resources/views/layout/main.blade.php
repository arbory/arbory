<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('arbory.title', 'Arbory') }}</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ Admin::assets()->getStaticUrl('images/favicon.ico') }}">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

        @include('arbory::layout.partials.assets-css')

    </head>
    <body class="view-index @if(isset($body_class)) {{ $body_class }}  @endif">

        @include('arbory::layout.partials.header')
        @include('arbory::layout.partials.menu')

        <main id="main">
            @yield('content.header')
            @include('arbory::layout.partials.message')

            @yield('content')
        </main>

        <div class="notifications" data-close-text="Close"></div>

        @include('arbory::layout.partials.environment')
        @include('arbory::layout.partials.assets-js')
    </body>
</html>
