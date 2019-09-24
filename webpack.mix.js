module.exports = function (mix) {

    mix.setPublicPath('public/arbory');
    mix.webpackConfig({resolve: {symlinks: false}});

    mix.js(
        'vendor/arbory/arbory/resources/assets/js/admin.js',
        'js/admin.js'
    );

    mix.js(
        'vendor/arbory/arbory/resources/assets/js/controllers/*',
        'js/controllers/'
    );

    mix.babel([
            'vendor/arbory/arbory/resources/assets/js/environment.js',
            'vendor/components/jquery/jquery.min.js',
            'vendor/components/jqueryui/jquery-ui.min.js',
            'vendor/components/jquery-cookie/jquery.cookie.js',
            'vendor/ckeditor/ckeditor/ckeditor.js',
            'vendor/ckeditor/ckeditor/adapters/jquery.js',
            'vendor/arbory/arbory/resources/assets/js/include/**/*.js',
        ],
        'public/arbory/js/application.js'
    );

    mix.sass(
        'vendor/arbory/arbory/resources/assets/stylesheets/application.scss',
        'css/application.css'
    );

    mix.sass(
        'vendor/arbory/arbory/resources/assets/stylesheets/controllers/nodes.scss',
        'css/controllers/'
    );

    mix.sass(
        'vendor/arbory/arbory/resources/assets/stylesheets/controllers/sessions.scss',
        'css/controllers/'
    );

    mix.copyDirectory(
        'vendor/ckeditor/ckeditor/',
        'public/arbory/ckeditor/'
    );

    mix.copyDirectory(
        'vendor/arbory/arbory/resources/assets/js/lib/ckeditor/plugins/',
        'public/arbory/ckeditor/plugins/'
    );

    mix.copyDirectory(
        'vendor/arbory/arbory/resources/assets/images/',
        'public/arbory/images/'
    );

    mix.copyDirectory(
        'vendor/unisharp/laravel-filemanager/public/',
        'public/arbory/laravel-filemanager/'
    );

    mix.version();
};