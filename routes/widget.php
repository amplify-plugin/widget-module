<?php

/*
|--------------------------------------------------------------------------
| Widget related routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'backpack'),
    'middleware' => array_merge(config('backpack.base.web_middleware', ['web']),
        (array) config('backpack.base.middleware_key', 'admin')),
    ['admin_password_reset_required'],
], function () {
    Route::group([
        'namespace' => 'Amplify\Widget\Controllers',
    ], function () {
        Route::crud('widget', 'WidgetCrudController');
    });
});
