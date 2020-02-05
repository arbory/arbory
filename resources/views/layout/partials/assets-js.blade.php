@foreach($assets->getJs() as $script)
    <script src="{{ $script }}"></script>
@endforeach

@foreach($assets->getInlineJs() as $inlineJs)
    <script type="text/javascript">
        {!! $inlineJs !!}
    </script>
@endforeach