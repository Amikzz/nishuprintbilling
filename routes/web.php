<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::resource('purchase-order-databases', 'App\Http\Controllers\PurchaseOrderDatabaseController');
