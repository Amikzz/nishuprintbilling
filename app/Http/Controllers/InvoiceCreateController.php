<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDatabase;
use App\Models\Items;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderDatabase;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceCreateController extends Controller
{
    public function createInvoice(Request $request, $invoice_number)
    {
        // Validate the exchange rate input
        $request->validate([
            'exchange_rate' => 'required|numeric|min:0',
        ]);

        // Fetch the exchange rate from the request
        $exchangeRate = $request->input('exchange_rate');

        // Fetch the invoice details based on the invoice number
        $invoice = InvoiceDatabase::where('invoice_no', $invoice_number)->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Fetch the related purchase order using the po_number and reference_no
        $purchaseOrder = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
            ->where('reference_no', $invoice->reference_no)
            ->first();

        if (!$purchaseOrder) {
            return response()->json(['error' => 'Purchase order not found'], 404);
        }

        // Fetch customer details
        $customer = Customer::find($purchaseOrder->customer_id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Fetch related purchase order items
        $purchaseOrderItems = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
            ->where('reference_no', $invoice->reference_no)
            ->get();

        if ($purchaseOrderItems->isEmpty()) {
            return response()->json(['error' => 'No items found for this purchase order'], 404);
        }

        // Fetch and map the item details
        $purchaseOrderItemsDetails = $purchaseOrderItems->map(function ($orderItem) {
            $item = Items::where('item_code', $orderItem->item_code)->first();

            $unit_price = ($orderItem->price) / $orderItem->po_qty;

            return (object) [
                'item_code' => $item ? $item->item_code : 'N/A',
                'item_name' => $item ? $item->name : 'Unknown Item',
                'color_no' => $orderItem->color_no,
                'color' => $orderItem->color_name,
                'size' => $orderItem->size,
                'po_qty' => $orderItem->po_qty,
                'unit_price' => $unit_price,
                'price' => ($unit_price*$orderItem->po_qty),
            ];
        });

        // Calculate the grand total in local currency
        $grandTotalLocal = $purchaseOrderItemsDetails->sum('price');

        // Calculate the grand total in the converted currency using the exchange rate
        $grandTotalConverted = $grandTotalLocal * $exchangeRate;

        // Split items into chunks of 30 for pagination
        $itemsPerPage = 30;
        $pages = $purchaseOrderItemsDetails->chunk($itemsPerPage);

        // Generate the invoice PDF view
        $pdf = Pdf::loadView('invoice', [
            'invoice' => $invoice,
            'pages' => $pages,
            'customer' => $customer,
            'grandTotalLocal' => $grandTotalLocal,
            'grandTotalConverted' => $grandTotalConverted,
            'exchangeRate' => $exchangeRate,
            'purchaseOrderItemsDetails' => $purchaseOrderItemsDetails,
        ]);

        // Ensure the storage directory exists
        $filePath = storage_path('app/public/invoices/' . $invoice->invoice_no . '.pdf');
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        // Save the PDF file
        file_put_contents($filePath, $pdf->output());

        // Flash a success message to the session
        session()->flash('success', 'Invoice created successfully!');

        // Redirect to the invoice index page with the success message
        return redirect()->route('invoice-databases.index');
    }

    public function orderDispatch($id)
    {
        try {
            // Find the invoice by ID
            $invoice = InvoiceDatabase::findOrFail($id);

            // Update the status to "Order Dispatched"
            $invoice->status = 'Order Dispatched';
            $invoice->save();

            // Flash a success message
            session()->flash('success', 'Invoice status updated to Order Dispatched.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Flash an error message if the invoice is not found
            session()->flash('error', 'Invoice not found.');
        } catch (\Exception $e) {
            // Flash a general error message for any other exceptions
            session()->flash('error', 'An error occurred while updating the invoice status.');
        }

        // Redirect back to the previous page
        return redirect()->back();
    }

    public function ordercomplete($id)
    {
        try {
            // Find the invoice by ID
            $invoice = InvoiceDatabase::findOrFail($id);

            // Update the status to "Order Dispatched"
            $invoice->status = 'Order Complete';
            $invoice->save();

            // Flash a success message
            session()->flash('success', 'Invoice status updated to Order Complete.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Flash an error message if the invoice is not found
            session()->flash('error', 'Invoice not found.');
        } catch (\Exception $e) {
            // Flash a general error message for any other exceptions
            session()->flash('error', 'An error occurred while updating the invoice status.');
        }

        // Redirect back to the previous page
        return redirect()->back();
    }
}
