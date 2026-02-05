<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\RecordController;
use App\Admin\Controllers\InventoryController;
use App\Admin\Controllers\OutcomeController;
use App\Admin\Controllers\SeatController;
use App\Admin\Controllers\AnnouncementController;
Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->get('/dashboard/online', 'HomeController@online')->name('online');
    $router->get('/dashboard/debt', 'HomeController@debt')->name('debt');
    $router->get('/dashboard/unpaid', 'HomeController@unpaid')->name('unpaid');
    $router->get('/dashboard/stock', 'HomeController@stock')->name('stock');
    $router->post('/records/subtract-drink-qty', 'RecordController@subtractDrinkQty')->name('subtract-drink-qty');
    $router->post('/records/{id}/subtract-drink-qty', 'RecordController@subtractDrinkQty')->name('subtract-drink-qty');
    $router->resource('records', RecordController::class);
    $router->resource('inventories', InventoryController::class);
    $router->resource('outcomes', OutcomeController::class);
    $router->resource('seats', SeatController::class);
    $router->resource('announcements', AnnouncementController::class);
});
