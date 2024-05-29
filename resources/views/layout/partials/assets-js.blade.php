<script type="text/javascript" src="/vendor/arbory/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/vendor/arbory/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="/vendor/arbory/jquery/jquery.magnific-popup.min.js"></script>

@foreach($assets->getJs() as $script)
    <script type="module" src="{{ asset($script) }}"></script>
@endforeach

@foreach($assets->getInlineJs() as $inlineJs)
    <script type="text/javascript">
        {!! $inlineJs !!}
    </script>
@endforeach
