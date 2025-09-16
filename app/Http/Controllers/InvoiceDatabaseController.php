<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceDatabaseRequest;
use App\Http\Requests\UpdateInvoiceDatabaseRequest;
use App\Models\InvoiceDatabase;
use App\Models\MasterSheet;
use App\Models\PurchaseOrderDatabase;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InvoiceDatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        // Initialize query builder
        $query = InvoiceDatabase::query();

        // Exclude invoices with 'Canceled' status
        $query->where('status', '!=', 'Order Complete');

        // Check for search terms in the request and apply filters
        if ($request->input('search') !== '' && $request->has('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', '%' . $search . '%')
                    ->orWhere('reference_no', 'like', '%' . $search . '%')
                    ->orWhere('po_number', 'like', '%' . $search . '%');
            });
        }

        // Check for date range in the request
        if ($request->input('start_date') !== null && $request->has('start_date') && $request->has('end_date') && $request->input('end_date') !== null) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Apply date filter (assuming the `date` column in your database stores the invoice date)
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        // Order by creation date (newest items first)
        $query->orderBy('created_at', 'desc');

        // Retrieve all results without pagination
        $invoices = $query->get();

        // Return the results to the view
        return view('invoicedelivery', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceDatabaseRequest $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceDatabase $invoiceDatabase): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoiceDatabase $invoiceDatabase): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceDatabaseRequest $request, InvoiceDatabase $invoiceDatabase): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceDatabase $invoiceDatabase): void
    {
        //
    }

    public function artworkNeed(Request $request, $id): RedirectResponse
    {
        try {
            // Validate the input
            $validated = $request->validate([
                'artwork_sent_by' => ['required', 'string', 'max:255'],
            ]);

            // Find the invoice by ID
            $invoice = InvoiceDatabase::findOrFail($id);

            // Check if the current status is 'Pending'
            if ($invoice->status === 'Pending') {
                // Update the status of the invoice to 'Artwork_sent'
                $invoice->status = 'Artwork_sent';
                $invoice->artwork_sent_by = $validated['artwork_sent_by'];
                $invoice->artwork_sent_date = now();
                $invoice->save();

                $mastersheet = MasterSheet::where('cust_ref', $invoice->po_number)->first();
                $mastersheet->art_sent_date = now();
                $mastersheet->status = 'pending';
                $mastersheet->save();

                // Get the PO number associated with the invoice
                $poNumber = $invoice->po_number;

                // Fetch all related purchase orders based on the PO number
                $purchaseOrders = PurchaseOrderDatabase::where('po_no', $poNumber)->get();

                // Check if related purchase orders exist
                if ($purchaseOrders->isEmpty()) {
                    session()?->flash('error', 'No related purchase orders found for this invoice.');
                    return redirect()->back();
                }

                // Update the status of each related purchase order to 'Artwork_sent'
                foreach ($purchaseOrders as $purchaseOrder) {
                    $purchaseOrder->status = 'Artwork_sent';  // Modify the status if needed
                    $purchaseOrder->save();
                }

                // Flash a success message
                session()?->flash('success', 'Purchase order and related items updated to Artwork Sent.');
                return redirect()->back();
            }

            // Flash an error message if the invoice status is not 'Pending'
            session()?->flash('error', 'Purchase order status is not Pending and cannot be updated.');
            return redirect()->back();
        } catch (ModelNotFoundException) {
            // Flash an error message if the purchase order is not found
            session()?->flash('error', 'Purchase order not found.');
            return redirect()->back();
        } catch (Exception) {
            // Flash a general error message for any other exceptions
            session()?->flash('error', 'An error occurred while updating the purchase order status.');
            return redirect()->back();
        }

    }

    public function artworkProduction(Request $request, $id): RedirectResponse
    {
        try {
            // Validate the input
            $validated = $request->validate([
                'artwork_approved_by' => ['required', 'string', 'max:255'],
            ]);

            // Find the invoice by ID
            $invoice = InvoiceDatabase::findOrFail($id);

            // Check if the current status is 'Artwork_sent'
            if ($invoice->status === 'Artwork_sent') {
                // Update the status of the invoice to 'Artwork_approved'
                $invoice->status = 'Artwork_approved';
                $invoice->artwork_approved_by = $validated['artwork_approved_by'];
                $invoice->artwork_approved_date = now();
                $invoice->save();

                $mastersheet = MasterSheet::where('cust_ref', $invoice->po_number)->first();
                $mastersheet->art_approved_date = now();
                $mastersheet->status = 'approved';
                $mastersheet->save();

                // Get the PO number associated with the invoice
                $poNumber = $invoice->po_number;

                // Fetch all related purchase orders based on the PO number
                $purchaseOrders = PurchaseOrderDatabase::where('po_no', $poNumber)->get();

                // Check if related purchase orders exist
                if ($purchaseOrders->isEmpty()) {
                    session()?->flash('error', 'No related purchase orders found for this invoice.');
                    return redirect()->back();
                }

                // Update the status of each related purchase order to 'Artwork_approved'
                foreach ($purchaseOrders as $purchaseOrder) {
                    $purchaseOrder->status = 'Artwork_approved';  // Modify the status if needed
                    $purchaseOrder->save();
                }

                // Flash a success message
                session()?->flash('success', 'Purchase order and related items updated to Artwork Approved.');
            } else {
                // Flash an error message if the invoice status is not 'Artwork_sent'
                session()?->flash('error', 'Purchase order status is not Artwork Sent and cannot be updated.');
            }
        } catch (ModelNotFoundException) {
            // Flash an error message if the purchase order is not found
            session()?->flash('error', 'Purchase order not found.');
        } catch (Exception) {
            // Flash a general error message for any other exceptions
            session()?->flash('error', 'An error occurred while updating the purchase order status.');
        }

        // Redirect back to the previous page
        return redirect()->back();

    }

    public function cancelInvoice($id): RedirectResponse
    {
        try {
            // Find the invoice by its ID
            $invoice = InvoiceDatabase::findOrFail($id);

            // Get the PO number associated with the invoice
            $poNumber = $invoice->po_number;

            // Update the status of all related purchase order items based on the PO number
            $purchaseOrders = PurchaseOrderDatabase::where('po_no', $poNumber)->get();

            // Check if there are any related purchase orders
            if ($purchaseOrders->isEmpty()) {
                session()?->flash('error', 'No related purchase orders found for this invoice.');
                return redirect()->back();
            }

            // Update the status of each related purchase order item to 'Canceled'
            foreach ($purchaseOrders as $purchaseOrder) {
                $purchaseOrder->status = 'Cancelled';  // You can modify the status as per your requirement
                $purchaseOrder->save();
            }

            // Now update the status of the invoice itself to 'Canceled'
            $invoice->status = 'Cancelled';
            $invoice->save();

            $mastersheet = MasterSheet::where('invoice_no', $invoice->invoice_no)->first();
            $mastersheet->status = 'cancelled';
            $mastersheet->save();

            // Flash a success message
            session()?->flash('success', 'Purchase order and related items cancelled successfully.');
        } catch (ModelNotFoundException) {
            // Flash an error message if the invoice is not found
            session()?->flash('error', 'Purchase order not found.');
        } catch (Exception) {
            // Flash a general error message for any other exceptions
            session()?->flash('error', 'An error occurred while cancelling the purchase order and related items.');
        }

        // Redirect back to the previous page
        return redirect()->back();
    }

    //items printed function
    public function itemsPrinted($invoice_id): ?RedirectResponse
    {
        try {
            // Find the invoice by ID
            $invoice = InvoiceDatabase::findOrFail($invoice_id);

            // Check if the current status is 'Artwork_approved'
            if ($invoice->status === 'Artwork_approved' || $invoice->status === 'Urgent') {
                // Update the status of the invoice to 'Items_printed'
                $invoice->status = 'Items_printed';
                $invoice->save();

                $mastersheet = MasterSheet::where('cust_ref', $invoice->po_number)->first();
                $mastersheet->print_date = now();
                $mastersheet->status = 'printed';
                $mastersheet->save();

                // Get the PO number associated with the invoice
                $poNumber = $invoice->po_number;

                // Fetch all related purchase orders based on the PO number
                $purchaseOrders = PurchaseOrderDatabase::where('po_no', $poNumber)->get();

                // Check if related purchase orders exist
                if ($purchaseOrders->isEmpty()) {
                    session()?->flash('error', 'No related purchase orders found for this invoice.');
                    return redirect()->back();
                }

                // Update the status of each related purchase order to 'Items_printed'
                foreach ($purchaseOrders as $purchaseOrder) {
                    $purchaseOrder->status = 'Items_printed';  // Modify the status if needed
                    $purchaseOrder->save();
                }

                // Flash a success message with return
                session()?->flash('success', 'Purchase order and related items updated to Items Printed.');
                return redirect()->back();
            }

            // Flash an error message if the invoice status is not 'Artwork_approved'
            session()?->flash('error', 'Purchase order status is not Artwork Approved and cannot be updated.');
            return redirect()->back();
        } catch (ModelNotFoundException) {
            // Flash an error message if the purchase order is not found
            session()?->flash('error', 'Purchase order not found.');
            return redirect()->back();

        } catch (Exception) {
            // Flash a general error message for any other exceptions
            session()?->flash('error', 'An error occurred while updating the purchase order status.');
            return redirect()->back();
        }
    }

    //items printed function
    public function itemsUrgent($invoice_id): ?RedirectResponse
    {
        try {
            // Find the invoice by ID
            $invoice = InvoiceDatabase::findOrFail($invoice_id);
            $invoice->status = 'Urgent';
            $invoice->save();

            $mastersheet = MasterSheet::where('cust_ref', $invoice->po_number)->first();
            $mastersheet->print_date = now();
            $mastersheet->status = 'urgent';
            $mastersheet->save();

            // Get the PO number associated with the invoice
            $poNumber = $invoice->po_number;

            // Fetch all related purchase orders based on the PO number
            $purchaseOrders = PurchaseOrderDatabase::where('po_no', $poNumber)->get();

            // Check if related purchase orders exist
            if ($purchaseOrders->isEmpty()) {
                session()?->flash('error', 'No related purchase orders found for this invoice.');
                return redirect()->back();
            }

            // Update the status of each related purchase order to 'Items_printed'
            foreach ($purchaseOrders as $purchaseOrder) {
                $purchaseOrder->status = 'Urgent';  // Modify the status if needed
                $purchaseOrder->save();
            }

            // Flash a success message with return
            session()?->flash('success', 'Purchase order and related items updated to Items Printed.');
            return redirect()->back();
        } catch (ModelNotFoundException) {
            // Flash an error message if the purchase order is not found
            session()?->flash('error', 'Purchase order not found.');
            return redirect()->back();
        } catch (Exception) {
            // Flash a general error message for any other exceptions
            session()?->flash('error', 'An error occurred while updating the purchase order status.');
            return redirect()->back();
        }
    }

    public function export($id): void
    {
        $invoice = InvoiceDatabase::findOrFail($id);

        // Fetch the all purchase order items using the po_number and reference_no from the invoice
        $purchaseOrders = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
            ->where('reference_no', $invoice->reference_no)
            ->get();

        $poNumber = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
            ->where('reference_no', $invoice->reference_no)
            ->first();

        // Define the filename for the Excel file
        $filename = $poNumber->po_no . ".xlsx";

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers for the Excel file
        $sheet->setCellValue('A1', 'COLOR NO');
        $sheet->setCellValue('B1', 'COLOR NAME');
        $sheet->setCellValue('C1', 'SIZE');
        $sheet->setCellValue('D1', 'STYLE');
        $sheet->setCellValue('E1', 'BARCODE');
        $sheet->setCellValue('F1', 'MORE 1');
        $sheet->setCellValue('G1', 'MORE 2');

        // Write purchase order records to the Excel sheet
        $row = 2; // Start writing data from row 2
        foreach ($purchaseOrders as $order) {
            // Format the cells as text and add leading zeros
            $sheet->setCellValueExplicit('A' . $row, $order->color_no ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $row, $order->color_name ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $row, $order->size ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $row, $order->style ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $row, $order->upc_no ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $row, $order->more1 ?? '-', DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $row, $order->more2 ?? '-', DataType::TYPE_STRING);
            $row++;
        }

        // Set headers for the file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Create the Excel writer and output to php://output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        // Return with success flash
        session()?->flash('success', 'Excel file exported successfully.');
    }
}
