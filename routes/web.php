<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\PurchaseOrderDatabaseController@create')->name('home');

Route::resource('purchase-order-databases', 'App\Http\Controllers\PurchaseOrderDatabaseController');
Route::resource('invoice-databases', 'App\Http\Controllers\InvoiceDatabaseController');
Route::get('invoice/create/{invoice_number}', 'App\Http\Controllers\InvoiceCreateController@createInvoice')->name('invoice.create');
Route::get('deliverynote/create/{invoice_number}', 'App\Http\Controllers\DeliveryNCreateController@createDeliveryNote')->name('deliverynote.create');
Route::post('purchaseorder/{id}', 'App\Http\Controllers\PurchaseOrderDatabaseController@artworkNeed')->name('purchaseorder.artwork');
Route::post('purchaseorderdone/{id}', 'App\Http\Controllers\PurchaseOrderDatabaseController@artworkProduction')->name('purchaseorder.artworkdone');
Route::post('orderdispatch/{id}', 'App\Http\Controllers\InvoiceCreateController@orderDispatch')->name('order.dispatch');
Route::post('ordercomplete/{id}', 'App\Http\Controllers\InvoiceCreateController@ordercomplete')->name('order.complete');
