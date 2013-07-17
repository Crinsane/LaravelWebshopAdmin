<?php

Route::filter('admin', function($request)
{
    Asset::add('bootstrap', 'packages/Amazings/Admin/assets/css/bootstrap.css');
    Asset::add('bootstrap-toppadding', 'packages/Amazings/Admin/assets/css/bootstrap-padding.css');
    Asset::add('bootstrap-responsive', 'packages/Amazings/Admin/assets/css/bootstrap-responsive.css');
    Asset::add('main-style', 'packages/Amazings/Admin/assets/css/style.css');

    Asset::add('jquery', 'packages/Amazings/Admin/assets/js/jquery.js');
    Asset::add('bootstrap', 'packages/Amazings/Admin/assets/js/bootstrap.js');
    Asset::add('bootbox', 'packages/Amazings/Admin/assets/js/bootbox.js');
    Asset::add('laravel', 'packages/Amazings/Admin/assets/js/laravel.js');
    Asset::add('main-scripts', 'packages/Amazings/Admin/assets/js/scripts.js');
});