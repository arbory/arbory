<!DOCTYPE html>
<html>
    <head>
        <title>Arbory</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link href="{{ mix('/css/application.css', "arbory") }}" media="all" rel="stylesheet"/>
        <link href="{{ mix('/css/controllers/nodes.css', "arbory") }}" media="all" rel="stylesheet"/>

        @foreach($assetsCss as $asset)
            <link href="{{ mix( $asset, "arbory" ) }}" media="all" rel="stylesheet"/>
        @endforeach

        @if($inlineCss)
            <style>
                {!! $inlineCss !!}
            </style>
        @endif
    </head>
    <body class="view-index @if(isset($body_class)) {{ $body_class }}  @endif">

        @include('arbory::layout.partials.header')
        @include('arbory::layout.partials.menu')

        <main id="main">
            @yield('content.header')
            @yield('content')
        </main>

        <div class="notifications" data-close-text="Close"></div>

        <script src="{{ mix('/js/application.js', "arbory") }}"></script>
        <script src="{{ mix('/js/controllers/nodes.js', "arbory") }}"></script>
        <script src="{{ mix('/js/admin.js', "arbory") }}"></script>

        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}">
        </script>

        @foreach($assetsJs as $asset)
            <script src="{{ mix( $asset, "arbory" ) }}"></script>
        @endforeach

        @if($inlineJs)
            <script type="text/javascript">
                {!! $inlineJs !!}
            </script>
        @endif
    </body>
</html>
