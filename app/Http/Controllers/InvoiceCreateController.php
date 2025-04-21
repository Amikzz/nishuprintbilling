<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Models\InvoiceDatabase;
use App\Models\Items;
use App\Models\MasterSheet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderDatabase;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class InvoiceCreateController extends Controller
{
    public function createInvoice(Request $request, $po_number)
    {
        // Validate the request
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:255',
        ]);

        // Fetch the exchange rate
        $exchangeRate = ExchangeRate::where('currency_from', 'USD')
            ->where('currency_to', 'LKR')
            ->first();

        // Log exchange rate fetch
        Log::info('Exchange rate fetched', ['rate' => $exchangeRate ? $exchangeRate->rate : 'Not found']);

        $exchangeRate = $exchangeRate ? $exchangeRate->rate : 0;

        // Fetch the invoice details based on the PO number
        $invoice = InvoiceDatabase::where('po_number', $po_number)->first();

        // Log invoice fetch
        Log::info('Invoice fetched', ['invoice' => $invoice ? $invoice->id : 'Not found']);

        if (!$invoice) {
            Log::error('Invoice not found for PO: ' . $po_number);
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Update invoice number and status
        $invoiceNo = 'NC-' . '25-26' . '-' . $validated['invoice_number'];
        $invoice->invoice_no = $invoiceNo;
        $invoice->status = 'Order Dispatched';
        $invoice->save();

        // Update master sheet
        $masterSheet = MasterSheet::where('cust_ref', $po_number)->first();
        if ($masterSheet) {
            $masterSheet->invoice_no = $invoiceNo;
            $masterSheet->invoice_date = now();
            $masterSheet->status = 'delivered';
            $masterSheet->save();
        }

        // Log master sheet update
        Log::info('Master sheet updated', ['invoice_no' => $invoiceNo]);

        // Fetch the related purchase order
        $purchaseOrder = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
            ->where('reference_no', $invoice->reference_no)
            ->first();

        // Log purchase order fetch
        Log::info('Purchase order fetched', ['purchase_order' => $purchaseOrder ? $purchaseOrder->id : 'Not found']);

        if (!$purchaseOrder) {
            Log::error('Purchase order not found for PO: ' . $po_number);
            return response()->json(['error' => 'Purchase order not found'], 404);
        }

        // Fetch customer details
        $customer = Customer::find($purchaseOrder->customer_id);

        // Log customer fetch
        Log::info('Customer fetched', ['customer' => $customer ? $customer->id : 'Not found']);

        if (!$customer) {
            Log::error('Customer not found for PO: ' . $po_number);
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Fetch related purchase order items
        $purchaseOrderItems = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
            ->where('reference_no', $invoice->reference_no)
            ->get();

        // Log purchase order items fetch

        if ($purchaseOrderItems->isEmpty()) {
            Log::error('No items found for PO: ' . $po_number);
            return response()->json(['error' => 'No items found for this purchase order'], 404);
        }

        // Optimize item fetching by querying all items at once
        $itemCodes = $purchaseOrderItems->pluck('item_code')->unique()->filter();
        $itemsCollection = Items::whereIn('item_code', $itemCodes)->get()->keyBy('item_code');

        // Map purchase order items with their details
        $purchaseOrderItemsDetails = $purchaseOrderItems->map(function ($orderItem) use ($itemsCollection) {
            $item = $itemsCollection[$orderItem->item_code] ?? null;
            $unit_price = ($orderItem->price) / $orderItem->po_qty;

            return (object) [
                'item_code' => $orderItem->item_code,
                'item_name' => $item ? $item->name : 'Unknown Item',
                'sticker_size' => $item ? $item->description : 'N/A',
                'color_no' => $orderItem->color_no,
                'color' => $orderItem->color_name,
                'size' => $orderItem->size,
                'po_qty' => $orderItem->po_qty,
                'unit_price' => $unit_price,
                'price' => ($unit_price * $orderItem->po_qty),
            ];
        });

        // Log item details mapping

        // Calculate the grand total in local currency
        $grandTotalLocal = $purchaseOrderItemsDetails->sum('price');

        // Calculate the grand total in the converted currency using the exchange rate
        $grandTotalConverted = $grandTotalLocal * $exchangeRate;

        // Split items into chunks of 30 for pagination
        $itemsPerPage = 30;
        $pages = $purchaseOrderItemsDetails->chunk($itemsPerPage);

        // Log PDF generation start

        try {
            // Generate the invoice PDF view
            $pdf = PDF::loadView('invoice', [
                'date' => now(),
                'invoice' => $invoice,
                'pages' => $pages,
                'customer' => $customer,
                'grandTotalLocal' => $grandTotalLocal,
                'grandTotalConverted' => $grandTotalConverted,
                'exchangeRate' => $exchangeRate,
                'purchaseOrderItemsDetails' => $purchaseOrderItemsDetails,
            ]);

            // Log PDF generation success
            Log::info('PDF generated successfully');

            // Return the PDF for download
            return $pdf->download($invoice->invoice_no . '.pdf');
        } catch (\Exception $e) {
            // Log PDF generation failure
            Log::error('PDF Generation Failed: ' . $e->getMessage());

            // Return an error response
            return response()->json(['error' => 'PDF generation failed. Please try again.'], 500);
        }
    }

    public function orderDispatch($id)
    {
        try {
            // Find the invoice by ID
            $invoice = InvoiceDatabase::findOrFail($id);

            // Update the status of the invoice to 'Order Dispatched'
            $invoice->status = 'Order Dispatched';
            $invoice->save();

            $mastersheet = MasterSheet::where('invoice_no', $invoice->invoice_no)->first();
            $mastersheet->status = 'delivered';
            $mastersheet->save();

            // Get the PO number associated with the invoice
            $poNumber = $invoice->po_number;

            // Fetch all related purchase orders based on the PO number
            $purchaseOrders = PurchaseOrderDatabase::where('po_no', $poNumber)->get();

            // Check if related purchase orders exist
            if ($purchaseOrders->isEmpty()) {
                session()->flash('error', 'No related purchase orders found for this invoice.');
                return redirect()->back();
            }

            // Update the status of each related purchase order to 'Order Dispatched'
            foreach ($purchaseOrders as $purchaseOrder) {
                $purchaseOrder->status = 'Order Dispatched';  // Modify the status if needed
                $purchaseOrder->save();
            }

            // Flash a success message
            session()->flash('success', 'Invoice and related purchase orders status updated to Order Dispatched.');

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

            // Update the status of the invoice to 'Order Complete'
            $invoice->status = 'Order Complete';
            $invoice->save();

            // Get the PO number associated with the invoice
            $poNumber = $invoice->po_number;

            // Fetch all related purchase orders based on the PO number
            $purchaseOrders = PurchaseOrderDatabase::where('po_no', $poNumber)->get();

            // Check if related purchase orders exist
            if ($purchaseOrders->isEmpty()) {
                session()->flash('error', 'No related purchase orders found for this invoice.');
                return redirect()->back();
            }

            // Update the status of each related purchase order to 'Order Complete'
            foreach ($purchaseOrders as $purchaseOrder) {
                $purchaseOrder->status = 'Order Complete';  // Modify the status if needed
                $purchaseOrder->save();
            }

            // Flash a success message
            session()->flash('success', 'Invoice and related purchase orders status updated to Order Complete.');

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
