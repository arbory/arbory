<!DOCTYPE html>
<html>
    <head>
        <title>Leaf</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link href="{{ mix('/leaf/css/application.css') }}" media="all" rel="stylesheet"/>
        <link href="{{ mix('/leaf/css/controllers/nodes.css') }}" media="all" rel="stylesheet"/>

        @foreach($assetsCss as $asset)
            <link href="{{ mix( $asset ) }}" media="all" rel="stylesheet"/>
        @endforeach

        @if($inlineCss)
            <style>
                {!! $inlineCss !!}
            </style>
        @endif
    </head>
    <body class="view-index @if(isset($body_class)) {{ $body_class }}  @endif">

        @include('leaf::layout.partials.header')
        @include('leaf::layout.partials.menu')

        <main id="main">
            @yield('content.header')
            @yield('content')
        </main>

        <div class="notifications" data-close-text="Close"></div>

        <script src="{{ mix('/leaf/js/application.js') }}"></script>
        <script src="{{ mix('/leaf/js/controllers/nodes.js') }}"></script>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}">
        </script>

        @foreach($assetsJs as $asset)
            <script src="{{ mix( $asset ) }}"></script>
        @endforeach

        @if($inlineJs)
            <script type="text/javascript">
                {!! $inlineJs !!}
            </script>
        @endif
    </body>
</html>
