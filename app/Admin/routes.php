<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use OpenAdmin\Admin\Facades\Admin;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->get('/items', 'ItemsController@index')->name('items');
    $router->post('/items', 'ItemsController@store')->name('items.store');
    $router->put('/items/{id}', 'ItemsController@update')->name('items.update');
    $router->delete('/items/{item_code}', 'ItemsController@destroy')->name('items.destroy');

    $router->get('/exchange', 'ExchangeRateController@index')->name('exchange');
    $router->put('/exchange/{id}', 'ExchangeRateController@update')->name('exchange.update');
});
