let fs = require('fs-extra');

const controllers = fs.readdirSync('vendor/cubesystems/leaf/resources/assets/js/controllers');

module.exports = function (mix) {

    mix.js(
        './vendor/cubesystems/leaf/resources/assets/js/admin.js',
        'public/leaf/js'
    );

    for (let name of controllers) {
        mix.js('vendor/cubesystems/leaf/resources/assets/js/controllers/' + name, 'public/leaf/js/controllers/');
    }

    mix.scripts([
            './vendor/cubesystems/leaf/resources/assets/js/environment.js',
            './vendor/components/jquery/jquery.min.js',
            './vendor/components/jqueryui/jquery-ui.min.js',
            './vendor/components/jquery-cookie/jquery.cookie.js',
            './vendor/ckeditor/ckeditor/ckeditor.js',
            './vendor/ckeditor/ckeditor/adapters/jquery.js',
            './vendor/cubesystems/leaf/resources/assets/js/include/**/*.js',
        ],
        'public/leaf/js/application.js'
    );

    mix.sass(
        'vendor/cubesystems/leaf/resources/assets/stylesheets/application.scss',
        'public/leaf/css/application.css'
    );

    mix.sass(
        'vendor/cubesystems/leaf/resources/assets/stylesheets/controllers/nodes.scss',
        'public/leaf/css/controllers/'
    );

    mix.sass(
        'vendor/cubesystems/leaf/resources/assets/stylesheets/controllers/sessions.scss',
        'public/leaf/css/controllers/'
    );

    mix.copyDirectory(
        'vendor/ckeditor/ckeditor/',
        'public/leaf/ckeditor/'
    );

    mix.copyDirectory(
        'vendor/cubesystems/leaf/resources/assets/js/lib/ckeditor/plugins/',
        'public/leaf/ckeditor/plugins/'
    );

    mix.copyDirectory(
        'vendor/cubesystems/leaf/resources/assets/images/',
        'public/leaf/images/'
    );
};
