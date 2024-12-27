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
Route::post('/search-returninvoice', 'App\Http\Controllers\ReturnController@searchInvoice')->name('search.returninvoice');
Route::delete('/delete-returnitem/{id}','App\Http\Controllers\ReturnController@deleteItem')->name('delete.returnitem');
Route::put('/update-return-items/{id}', 'App\Http\Controllers\ReturnController@update')->name('update.returnitem');
Route::get('/view-updated', 'App\Http\Controllers\ReturnController@viewUpdated')->name('view.updated.records');
Route::post('/cancel/{id}', 'App\Http\Controllers\InvoiceDatabaseController@cancelInvoice')->name('cancel.invoice');
Route::get('/invoicedetails/{id}', 'App\Http\Controllers\InvoiceDatabaseController@export')->name('invoice.details');
Route::post('/deliverynote/return/{d_note_no}', 'App\Http\Controllers\ReturnController@createDeliveryNote')->name('deliverynote.return');
Route::get('/mastersheet', 'App\Http\Controllers\MasterSheetController@getMasterSheet')->name('mastersheet');
Route::post('/mastersheet/create', 'App\Http\Controllers\MasterSheetController@createMasterSheet')->name('mastersheet.create');
Route::get('/purchaseorder/printed/{invoice_id}', 'App\Http\Controllers\InvoiceDatabaseController@itemsPrinted')->name('purchaseorder.printed');

//Report Routes
Route::get('/reports/invoices', 'App\Http\Controllers\ReportGenerateController@invoiceReport')->name('report.invoices');
Route::get('/reports/pending', 'App\Http\Controllers\ReportGenerateController@pendingListReport')->name('report.pendinglist');
Route::get('/reports/mastersheet', 'App\Http\Controllers\ReportGenerateController@masterSheetReport')->name('report.mastersheet');
Route::get('/reports/complete', 'App\Http\Controllers\ReportGenerateController@completeOrderReport')->name('report.completeorders');
Route::get('/reports/allorders', 'App\Http\Controllers\ReportGenerateController@purchaseOrderReport')->name('report.allorders');
Route::get('/reports/sales', 'App\Http\Controllers\ReportGenerateController@salesReport')->name('report.sales');
