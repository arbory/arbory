module.exports = function(elixir){

    elixir(mix => {

        mix.scriptsIn(
            'packages/CubeSystems/Leaf/resources/assets/javascripts/include/',
            'public/leaf/js/dependencies.min.js'
        );

        mix.scripts(
            [
                'jquery/jquery.min.js',
                'jqueryui/jquery-ui.min.js',
                'jquery-cookie/jquery.cookie.js',
                '../ckeditor/ckeditor/ckeditor.js',
                '../ckeditor/ckeditor/adapters/jquery.js',
            ],
            'public/leaf/js/vendor.min.js',
            'packages/CubeSystems/Leaf/vendor/components/'
        );

        mix.scripts([
                'vendor.min.js',
                'dependencies.min.js',
            ],
            'public/leaf/js/application.js',
            'public/leaf/js/'
        );

        mix.copy(
            'packages/CubeSystems/Leaf/resources/assets/javascripts/controllers/',
            'public/leaf/js/controllers/'
        );

        mix.sass(
            'application.scss',
            'public/leaf/css/application.css',
            'packages/CubeSystems/Leaf/resources/assets/stylesheets/'
        );

        mix.sass(
            'controllers/nodes.scss',
            'public/leaf/css/controllers/',
            'packages/CubeSystems/Leaf/resources/assets/stylesheets/'
        );

        mix.sass(
            'controllers/sessions.scss',
            'public/leaf/css/controllers/',
            'packages/CubeSystems/Leaf/resources/assets/stylesheets/'
        );

        mix.copy(
            'packages/CubeSystems/Leaf/vendor/ckeditor/ckeditor/',
            'public/leaf/ckeditor/'
        );

        mix.copy(
            'packages/CubeSystems/Leaf/resources/assets/images/',
            'public/leaf/images/'
        );

    });
};