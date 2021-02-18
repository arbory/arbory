<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('arbory.title', 'Arbory') }}</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link href="{{ mix('/css/application.css', 'arbory') }}" media="all" rel="stylesheet"/>
        <link href="{{ mix('/css/controllers/nodes.css', 'arbory') }}" media="all" rel="stylesheet"/>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @foreach($assets->getCss() as $css)
            <link href="{{ $css }}" media="all" rel="stylesheet"/>
        @endforeach

        @foreach($assets->getInlineCss() as $style)
            <style>
                {!! $style !!}
            </style>
        @endforeach
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
        @if(! empty(config('arbory.services.google.maps_api_key', null)))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('arbory.services.google.maps_api_key') }}&libraries=places"></script>
        @endif
        @include('arbory::layout.partials.environment')

        <script src="{{ mix('js/manifest.js', 'arbory') }}"></script>
        <script src="{{ mix('js/vendor.js', 'arbory') }}"></script>
        <script src="{{ mix('js/application.js', 'arbory') }}"></script>
        <script src="{{ mix('js/controllers/nodes.js', 'arbory') }}"></script>

        @foreach($assets->getJs() as $script)
            <script src="{{ mix($script, 'arbory') }}"></script>
        @endforeach

        @foreach($assets->getInlineJs() as $inlineJs)
            <script type="text/javascript">
                {!! $inlineJs !!}
            </script>
        @endforeach
    </body>
</html>
