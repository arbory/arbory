<!DOCTYPE html>
<html>
    <head>
        <title>Arbory</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link href="{{ mix('/arbory/css/application.css') }}" media="all" rel="stylesheet"/>
        <link href="{{ mix('/arbory/css/controllers/nodes.css') }}" media="all" rel="stylesheet"/>

        <meta name="csrf-token" content="{{ csrf_token() }}">

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

        @include('arbory::layout.partials.header')
        @include('arbory::layout.partials.menu')

        <main id="main">
            @yield('content.header')
            @yield('content')
        </main>

        <div class="notifications" data-close-text="Close"></div>

        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>

        <script src="{{ mix('/arbory/js/application.js') }}"></script>
        <script src="{{ mix('/arbory/js/controllers/nodes.js') }}"></script>
        <script src="{{ mix('/arbory/js/admin.js') }}"></script>

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
