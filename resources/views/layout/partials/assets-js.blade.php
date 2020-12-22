@foreach($assets->getJs() as $script)
    <script src="{{ asset($script) }}"></script>
@endforeach

@foreach($assets->getInlineJs() as $inlineJs)
    <script type="text/javascript">
        {!! $inlineJs !!}
    </script>
@endforeach
