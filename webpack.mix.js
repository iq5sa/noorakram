/*
 * Copyright (c) 2023. noorakram.com
 *
 *
 */
const mix = require('laravel-mix');
require('laravel-mix-serve');
// mix.setPublicPath('public/dist');


//compiling js files
mix.js('resources/js/app.js', 'public/dist/js').version();


mix.css("resources/css/animate.min.css", "public/dist/css")
    .css("resources/css/fontawesome.min.css", "public/dist/css")
    .css("resources/css/flaticon.css", "public/dist/css")
    .css("resources/css/magnific-popup.css", "public/dist/css")
    .css("resources/css/default.min.css", "public/dist/css")
    .css("resources/css/owl-carousel.min.css", "public/dist/css")
    .css("resources/css/nice-select.css", "public/dist/css")
    .css("resources/css/slick.css", "public/dist/css")
    .css("resources/css/toastr.min.css", "public/dist/css")
    .css("resources/css/datatables-1.10.23.min.css", "public/dist/css")
    .css("resources/css/datatables.bootstrap4.min.css", "public/dist/css")
    .css("resources/css/responsive.dataTables.min.css", "public/dist/css")
    .css("resources/css/monokai-sublime.css", "public/dist/css")
    .css("resources/css/jquery-ui.min.css", "public/dist/css")
    .css("resources/css/video.min.css", "public/dist/css")
    .css("resources/css/responsive.css", "public/dist/css")
    .css("resources/css/mega-menu.css", "public/dist/css")
    .css("resources/css/rtl.css", "public/dist/css")
    .css("resources/css/rtl-responsive.css", "public/dist/css").version();


mix.sass("resources/scss/main.scss", "public/dist/css").version()

