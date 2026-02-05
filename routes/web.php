<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Simple admin seats page (renders seats view styled like dashboard online)
// Route::get('/admin/seats', function () {
//     return view('vendor.laravel-admin.dashboard.seats');
// });

