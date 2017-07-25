let fs = require('fs-extra');

const controllers = fs.readdirSync('vendor/arbory/base/resources/assets/js/controllers');

module.exports = function (mix) {

    mix.js(
        './vendor/arbory/base/resources/assets/js/admin.js',
        'public/arbory/js'
    );

    for (let name of controllers) {
        mix.js('vendor/arbory/base/resources/assets/js/controllers/' + name, 'public/arbory/js/controllers/');
    }

    mix.scripts([
            './vendor/arbory/base/resources/assets/js/environment.js',
            './vendor/components/jquery/jquery.min.js',
            './vendor/components/jqueryui/jquery-ui.min.js',
            './vendor/components/jquery-cookie/jquery.cookie.js',
            './vendor/ckeditor/ckeditor/ckeditor.js',
            './vendor/ckeditor/ckeditor/adapters/jquery.js',
            './vendor/arbory/base/resources/assets/js/include/**/*.js',
        ],
        'public/arbory/js/application.js'
    );

    mix.sass(
        'vendor/arbory/base/resources/assets/stylesheets/application.scss',
        'public/arbory/css/application.css'
    );

    mix.sass(
        'vendor/arbory/base/resources/assets/stylesheets/controllers/nodes.scss',
        'public/arbory/css/controllers/'
    );

    mix.sass(
        'vendor/arbory/base/resources/assets/stylesheets/controllers/sessions.scss',
        'public/arbory/css/controllers/'
    );

    mix.copyDirectory(
        'vendor/ckeditor/ckeditor/',
        'public/arbory/ckeditor/'
    );

    mix.copyDirectory(
        'vendor/arbory/base/resources/assets/js/lib/ckeditor/plugins/',
        'public/arbory/ckeditor/plugins/'
    );

    mix.copyDirectory(
        'vendor/arbory/base/resources/assets/images/',
        'public/arbory/images/'
    );
};
