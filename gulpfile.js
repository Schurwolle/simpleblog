var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

    mix.styles([
    	'font-awesome.min.css',
    	'bootstrap.min.css',
    	'parsley.css',
    	'select2.min.css',
    	'croppic.css',
    	'jquery.bxslider.css',
    	'sweetalert.css',
    	'default.css'
    ], null, 'public/');

    mix.scripts([
    	'jquery-2.2.2.min.js',
    	'bootstrap.min.js',
    	'croppic.min.js',
    	'jquery.bxslider.min.js',
    	'jquery.mousewheel.min.js',
    	'select2.min.js',
    	'sweetalert.min.js'
    	], null, 'public/')

    mix.version([
    	'css/all.css',
    	'js/all.js'
    ]);
});
