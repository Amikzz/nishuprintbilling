<?php

use App\Http\Controllers\DeliveryNCreateController;
use App\Http\Controllers\InvoiceCreateController;
use App\Http\Controllers\InvoiceDatabaseController;
use App\Http\Controllers\MasterSheetController;
use App\Http\Controllers\PurchaseOrderDatabaseController;
use App\Http\Controllers\ReportGenerateController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\UrgentController;
use Illuminate\Support\Facades\Route;

// Controllers

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| This file defines all application routes. Routes are grouped and
| documented by feature/module for better maintainability.
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [PurchaseOrderDatabaseController::class, 'create'])->name('home');

/*
|--------------------------------------------------------------------------
| Purchase Orders
|--------------------------------------------------------------------------
*/
Route::resource('purchase-order-databases', PurchaseOrderDatabaseController::class);

Route::prefix('purchaseorder')->name('purchaseorder.')->group(function () {
    Route::post('{id}', [InvoiceDatabaseController::class, 'artworkNeed'])->name('artwork');
    Route::post('done/{id}', [InvoiceDatabaseController::class, 'artworkProduction'])->name('artworkdone');
    Route::get('printed/{invoice_id}', [InvoiceDatabaseController::class, 'itemsPrinted'])->name('printed');
    Route::get('urgent/{invoice_id}', [InvoiceDatabaseController::class, 'itemsUrgent'])->name('urgent');
});

Route::get('/export', [PurchaseOrderDatabaseController::class, 'export'])->name('purchase-order-databases.export');

/*
|--------------------------------------------------------------------------
| Invoices
|--------------------------------------------------------------------------
*/
Route::resource('invoice-databases', InvoiceDatabaseController::class);

Route::prefix('invoice')->name('invoice.')->group(function () {
    Route::get('create/{po_number}', [InvoiceCreateController::class, 'createInvoice'])->name('create');
    Route::get('{invoice_id}/edit', [PurchaseOrderDatabaseController::class, 'edit'])->name('edit');
    Route::put('{invoiceId}', [PurchaseOrderDatabaseController::class, 'update'])->name('update');
    Route::post('dispatch/{id}', [InvoiceCreateController::class, 'orderDispatch'])->name('dispatch');
    Route::post('complete/{id}', [InvoiceCreateController::class, 'orderComplete'])->name('complete');
    Route::post('cancel/{id}', [InvoiceDatabaseController::class, 'cancelInvoice'])->name('cancel');
    Route::get('details/{id}', [InvoiceDatabaseController::class, 'export'])->name('details');
});

/*
|--------------------------------------------------------------------------
| Delivery Notes
|--------------------------------------------------------------------------
*/
Route::prefix('deliverynote')->name('deliverynote.')->group(function () {
    Route::get('create/{po_number}', [DeliveryNCreateController::class, 'createDeliveryNote'])->name('create');
    Route::post('return/{d_note_no}', [ReturnController::class, 'createDeliveryNote'])->name('return');
});

/*
|--------------------------------------------------------------------------
| Returns
|--------------------------------------------------------------------------
*/
Route::prefix('returns')->name('return.')->group(function () {
    Route::view('/', 'returns')->name('page');
    Route::post('search-invoice', [ReturnController::class, 'searchInvoice'])->name('searchinvoice');
    Route::delete('delete-item/{id}', [ReturnController::class, 'deleteItem'])->name('deleteitem');
    Route::put('update-items/{id}', [ReturnController::class, 'update'])->name('updateitem');
    Route::get('view-updated', [ReturnController::class, 'viewUpdated'])->name('viewupdated');
});

/*
|--------------------------------------------------------------------------
| Master Sheet
|--------------------------------------------------------------------------
*/
Route::prefix('mastersheet')->name('mastersheet.')->group(function () {
    Route::get('/', [MasterSheetController::class, 'getMasterSheet'])->name('index');
    Route::post('create', [MasterSheetController::class, 'createMasterSheet'])->name('create');
});

/*
|--------------------------------------------------------------------------
| Urgent Orders
|--------------------------------------------------------------------------
*/
Route::get('/urgentorders', [UrgentController::class, 'getMasterSheet'])->name('urgentorders');

/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/
Route::prefix('reports')->name('report.')->group(function () {
    Route::view('/', 'reportindex')->name('index');
    Route::get('invoices', [ReportGenerateController::class, 'invoiceReport'])->name('invoices');
    Route::get('pending', [ReportGenerateController::class, 'pendingListReport'])->name('pendinglist');
    Route::get('mastersheet', [ReportGenerateController::class, 'masterSheetReport'])->name('mastersheet');
    Route::get('complete', [ReportGenerateController::class, 'completeOrderReport'])->name('completeorders');
    Route::get('allorders', [ReportGenerateController::class, 'purchaseOrderReport'])->name('allorders');
    Route::get('sales', [ReportGenerateController::class, 'salesReport'])->name('sales');
});
