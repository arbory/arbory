@foreach($assets->getCss() as $css)
    <link href="{{ asset($css) }}" media="all" rel="stylesheet"/>
@endforeach

@foreach($assets->getInlineCss() as $style)
    <style>
        {!! $style !!}
    </style>
@endforeach
