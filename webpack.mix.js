module.exports = function (mix) {

    mix.combine(
        './vendor/cubesystems/leaf/resources/assets/javascripts/include/**/*.js',
        './public/leaf/js/dependencies.min.js'
    );

    mix.scripts(
        [
            './vendor/components/jquery/jquery.min.js',
            './vendor/components/jqueryui/jquery-ui.min.js',
            './vendor/components/jquery-cookie/jquery.cookie.js',
            './vendor/ckeditor/ckeditor/ckeditor.js',
            './vendor/ckeditor/ckeditor/adapters/jquery.js',
        ],
        './public/leaf/js/vendor.min.js'
    );

    mix.scripts([
            './vendor/cubesystems/leaf/resources/assets/javascripts/environment.js',
            './public/leaf/js/vendor.min.js',
            './public/leaf/js/dependencies.min.js',
        ],
        './public/leaf/js/application.js'
    );

    mix.copyDirectory(
        './vendor/cubesystems/leaf/resources/assets/javascripts/controllers/',
        './public/leaf/js/controllers/'
    );

    mix.sass(
        './vendor/cubesystems/leaf/resources/assets/stylesheets/application.scss',
        './public/leaf/css/application.css'
    );

    mix.sass(
        './vendor/cubesystems/leaf/resources/assets/stylesheets/controllers/nodes.scss',
        './public/leaf/css/controllers/'
    );

    mix.sass(
        './vendor/cubesystems/leaf/resources/assets/stylesheets/controllers/sessions.scss',
        './public/leaf/css/controllers/'
    );

    mix.copyDirectory(
        './vendor/ckeditor/ckeditor/',
        './public/leaf/ckeditor/'
    );

    mix.copyDirectory(
        './vendor/cubesystems/leaf/resources/assets/images/',
        './public/leaf/images/'
    );

};
