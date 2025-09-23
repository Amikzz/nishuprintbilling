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

Route::get('/', [PurchaseOrderDatabaseController::class, 'create'])->name('home');

Route::resource('purchase-order-databases', PurchaseOrderDatabaseController::class);
Route::resource('invoice-databases', InvoiceDatabaseController::class);

Route::get('invoice/create/{po_number}', [InvoiceCreateController::class, 'createInvoice'])->name('invoice.create');
Route::get('deliverynote/create/{po_number}', [DeliveryNCreateController::class, 'createDeliveryNote'])->name('deliverynote.create');
Route::get('deliverynote/download/{po_number}', [DeliveryNCreateController::class, 'downloadDeliveryNote'])->name('deliverynote.download');

Route::post('purchaseorder/{id}', [InvoiceDatabaseController::class, 'artworkNeed'])->name('purchaseorder.artwork');
Route::post('purchaseorderdone/{id}', [InvoiceDatabaseController::class, 'artworkProduction'])->name('purchaseorder.artworkdone');
Route::post('orderdispatch/{id}', [InvoiceCreateController::class, 'orderDispatch'])->name('order.dispatch');
Route::post('ordercomplete/{id}', [InvoiceCreateController::class, 'ordercomplete'])->name('order.complete');

Route::view('/reports', 'reportindex')->name('reports.page');

Route::get('/export', [PurchaseOrderDatabaseController::class, 'export'])->name('purchase-order-databases.export');
Route::get('/invoices/{invoice_id}/edit', [PurchaseOrderDatabaseController::class, 'edit'])->name('invoices.edit');
Route::put('/invoices/{invoiceId}', [PurchaseOrderDatabaseController::class, 'update'])->name('invoices.update');

Route::view('/return', 'returns')->name('return.page');
Route::post('/search-returninvoice', [ReturnController::class, 'searchInvoice'])->name('search.returninvoice');
Route::delete('/delete-returnitem/{id}', [ReturnController::class, 'deleteItem'])->name('delete.returnitem');
Route::put('/update-return-items/{id}', [ReturnController::class, 'update'])->name('update.returnitem');
Route::get('/view-updated', [ReturnController::class, 'viewUpdated'])->name('view.updated.records');
Route::post('/cancel/{id}', [InvoiceDatabaseController::class, 'cancelInvoice'])->name('cancel.invoice');
Route::get('/invoicedetails/{id}', [InvoiceDatabaseController::class, 'export'])->name('invoice.details');
Route::post('/deliverynote/return/{d_note_no}', [ReturnController::class, 'createDeliveryNote'])->name('deliverynote.return');

Route::get('/mastersheet', [MasterSheetController::class, 'getMasterSheet'])->name('mastersheet');
Route::post('/mastersheet/create', [MasterSheetController::class, 'createMasterSheet'])->name('mastersheet.create');

Route::get('/purchaseorder/printed/{invoice_id}', [InvoiceDatabaseController::class, 'itemsPrinted'])->name('purchaseorder.printed');
Route::get('/purchaseorder/urgent/{invoice_id}', [InvoiceDatabaseController::class, 'itemsUrgent'])->name('purchaseorder.urgent');

Route::get('/urgentorders', [UrgentController::class, 'getMasterSheet'])->name('urgentorders');

// Report Routes
Route::get('/reports/invoices', [ReportGenerateController::class, 'invoiceReport'])->name('report.invoices');
Route::get('/reports/pending', [ReportGenerateController::class, 'pendingListReport'])->name('report.pendinglist');
Route::get('/reports/mastersheet', [ReportGenerateController::class, 'masterSheetReport'])->name('report.mastersheet');
Route::get('/reports/complete', [ReportGenerateController::class, 'completeOrderReport'])->name('report.completeorders');
Route::get('/reports/allorders', [ReportGenerateController::class, 'purchaseOrderReport'])->name('report.allorders');
Route::get('/reports/sales', [ReportGenerateController::class, 'salesReport'])->name('report.sales');
