<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\PurchaseOrderDatabaseController@create')->name('home');

Route::resource('purchase-order-databases', 'App\Http\Controllers\PurchaseOrderDatabaseController');
Route::resource('invoice-databases', 'App\Http\Controllers\InvoiceDatabaseController');
Route::get('invoice/create/{invoice_number}', 'App\Http\Controllers\InvoiceCreateController@createInvoice')->name('invoice.create');
Route::get('deliverynote/create/{invoice_number}', 'App\Http\Controllers\DeliveryNCreateController@createDeliveryNote')->name('deliverynote.create');
Route::post('purchaseorder/{id}', 'App\Http\Controllers\InvoiceDatabaseController@artworkNeed')->name('purchaseorder.artwork');
Route::post('purchaseorderdone/{id}', 'App\Http\Controllers\InvoiceDatabaseController@artworkProduction')->name('purchaseorder.artworkdone');
Route::post('orderdispatch/{id}', 'App\Http\Controllers\InvoiceCreateController@orderDispatch')->name('order.dispatch');
Route::post('ordercomplete/{id}', 'App\Http\Controllers\InvoiceCreateController@ordercomplete')->name('order.complete');
Route::view('/reports', 'reportindex')->name('reports.page');
Route::get('/export', 'App\Http\Controllers\PurchaseOrderDatabaseController@export')->name('purchase-order-databases.export');
Route::get('/invoices/{invoice_id}/edit', 'App\Http\Controllers\PurchaseOrderDatabaseController@edit')->name('invoices.edit');
Route::put('/invoices/{invoiceId}', 'App\Http\Controllers\PurchaseOrderDatabaseController@update')->name('invoices.update');
Route::view('/return', 'returns')->name('return.page');
Route::get('/search-returninvoice', 'App\Http\Controllers\ReturnController@searchInvoice')->name('search.returninvoice');
Route::delete('/delete-returnitem/{id}','App\Http\Controllers\ReturnController@deleteItem')->name('delete.returnitem');
Route::put('/update-returnitem/{id}', 'App\Http\Controllers\ReturnController@updateItem')->name('update.returnitem');
Route::get('/view-updated', 'App\Http\Controllers\ReturnController@viewUpdated')->name('view.updated.records');
Route::post('/cancel/{id}', 'App\Http\Controllers\InvoiceDatabaseController@cancelInvoice')->name('cancel.invoice');
Route::get('/invoicedetails/{id}', 'App\Http\Controllers\InvoiceDatabaseController@export')->name('invoice.details');
