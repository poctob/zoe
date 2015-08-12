var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

/*elixir(function(mix) {
    mix.less('app.less');
});*/

var bowerDir = './resources/assets/vendor/';
 
var lessPaths = [
    bowerDir + "bootstrap/less",
    bowerDir + "font-awesome/less",
    bowerDir + "bootstrap-select/less"
];
 
elixir(function(mix) {
    mix.less('app.less', 'public/css', { paths: lessPaths })
        .scripts([
            'jquery/dist/jquery.min.js',
            'bootstrap/dist/js/bootstrap.min.js',
            'bootstrap-select/dist/js/bootstrap-select.min.js',
            'dropzone/js/dropzone.js'
            ], 'public/js/vendor.js', bowerDir)
        .copy('resources/assets/js/app.js', 'public/js/app.js')
        .copy(bowerDir + 'font-awesome/fonts', 'public/fonts');
 
});
