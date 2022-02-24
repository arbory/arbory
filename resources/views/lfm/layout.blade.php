<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EDGE" />
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#333844">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#333844">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#333844">

    <title>{{ trans('laravel-filemanager::lfm.title-page') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/vendor/laravel-filemanager/img/72px color.png') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.1/jquery-ui.min.css">
    <link rel="stylesheet" href="{{ asset('/vendor/laravel-filemanager/css/cropper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/laravel-filemanager/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/laravel-filemanager/css/mime-icons.min.css') }}">
    <style>{!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/css/lfm.css')) !!}</style>
    {{-- Use the line below instead of the above if you need to cache the css. --}}
    {{-- <link rel="stylesheet" href="{{ asset('/vendor/laravel-filemanager/css/lfm.css') }}"> --}}
</head>
<body>
@yield('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
<script src="{{ asset('/vendor/laravel-filemanager/js/cropper.min.js') }}"></script>
<script src="{{ asset('/vendor/laravel-filemanager/js/dropzone.min.js') }}"></script>
<script>
    var lang = {!! json_encode(trans('laravel-filemanager::lfm')) !!};
    var actions = [
        // {
        //   name: 'use',
        //   icon: 'check',
        //   label: 'Confirm',
        //   multiple: true
        // },
        {
            name: 'rename',
            icon: 'edit',
            label: lang['menu-rename'],
            multiple: false
        },
        {
            name: 'download',
            icon: 'download',
            label: lang['menu-download'],
            multiple: true
        },
        // {
        //   name: 'preview',
        //   icon: 'image',
        //   label: lang['menu-view'],
        //   multiple: true
        // },
        {
            name: 'move',
            icon: 'paste',
            label: lang['menu-move'],
            multiple: true
        },
        {
            name: 'resize',
            icon: 'arrows-alt',
            label: lang['menu-resize'],
            multiple: false
        },
        {
            name: 'crop',
            icon: 'crop',
            label: lang['menu-crop'],
            multiple: false
        },
        {
            name: 'trash',
            icon: 'trash',
            label: lang['menu-delete'],
            multiple: true
        },
    ];

    var sortings = [
        {
            by: 'alphabetic',
            icon: 'sort-alpha-down',
            label: lang['nav-sort-alphabetic']
        },
        {
            by: 'time',
            icon: 'sort-numeric-down',
            label: lang['nav-sort-time']
        }
    ];
</script>
<script>{!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/script.js')) !!}</script>
{{-- Use the line below instead of the above if you need to cache the script. --}}
{{-- <script src="{{ asset('/vendor/laravel-filemanager/js/script.js') }}"></script> --}}
<script>
    Dropzone.options.uploadForm = {
        paramName: "upload[]", // The name that will be used to transfer the file
        uploadMultiple: false,
        parallelUploads: 5,
        timeout:0,
        clickable: '#upload-button',
        dictDefaultMessage: lang['message-drop'],
        init: function() {
            var _this = this; // For the closure
            this.on('success', function(file, response) {
                if (response == 'OK') {
                    loadFolders();
                } else {
                    this.defaultOptions.error(file, response.join('\n'));
                }
            });
        },
        headers: {
            'Authorization': 'Bearer ' + getUrlParam('token')
        },
        acceptedFiles: "{{ implode(',', $helper->availableMimeTypes()) }}",
        maxFilesize: ({{ $helper->maxUploadSize() }} / 1000)
    }
</script>
</body>
</html>
