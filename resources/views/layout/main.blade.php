<!DOCTYPE html>
<html>
    <head>
        <title>Leaf</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <link href="/leaf/css/application.css" media="all" rel="stylesheet"/>
        <link href="/leaf/css/controllers/nodes.css" media="all" rel="stylesheet"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    </head>
    <body class="view-index @if(isset($body_class)) {{$body_class}}  @endif">

        @include('leaf::layout.partials.header')
        @include('leaf::layout.partials.menu')

        <main id="main">
            @yield('content.header')
            @yield('content')
        </main>

        <div class="notifications" data-close-text="Close"></div>

        <script src="/leaf/js/application.js"></script>
        <script src="/leaf/js/controllers/nodes.js"></script>
        <script src="//cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
    </body>
</html>
