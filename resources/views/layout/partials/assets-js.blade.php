<script type="text/javascript" src="/vendor/arbory/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/vendor/arbory/jquery/jquery-ui.min.js"></script>
@vite('resources/assets/js/application.js', 'vendor/arbory')
@foreach($assets->getJs() as $script)
    <script type="module" src="{{ asset($script) }}"></script>
@endforeach

@foreach($assets->getInlineJs() as $inlineJs)
    <script type="text/javascript">
        {!! $inlineJs !!}
    </script>
@endforeach
