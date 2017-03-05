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

// SaSS example
/** elixir(function(mix) {
  *     mix.sass('app.scss');
  * });
  */

//TODO: the css file outputs at it's old location for now, move to a dist/ style directory later
elixir(function(mix) {
    mix.styles('./../css/*.css', './../css/style.css');
});
