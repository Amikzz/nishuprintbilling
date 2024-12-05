<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::resource('purchase-order-databases', 'App\Http\Controllers\PurchaseOrderDatabaseController');
Route::resource('invoice-databases', 'App\Http\Controllers\InvoiceDatabaseController');
Route::get('invoice/create/{invoice_number}', 'App\Http\Controllers\InvoiceCreateController@createInvoice')->name('invoice.create');
Route::get('deliverynote/create/{invoice_number}', 'App\Http\Controllers\DeliveryNCreateController@createDeliveryNote')->name('deliverynote.create');
