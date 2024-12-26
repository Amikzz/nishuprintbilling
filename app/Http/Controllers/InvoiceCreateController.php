<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Models\InvoiceDatabase;
use App\Models\Items;
use App\Models\MasterSheet;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderDatabase;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceCreateController extends Controller
{
    public function createInvoice($invoice_number)
    {
        //Get the exchnage rate from the exchange_rates table
        $exchangeRate = ExchangeRate::where('currency_from', 'USD')->where('currency_to', 'LKR')->first();

        // Fetch the exchange rate from the request
        $exchangeRate = $exchangeRate ? $exchangeRate->rate : 0;

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
                'sticker_size' => $item ? $item->description : 'N/A',
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

        return $pdf->download($invoice->invoice_no . '.pdf');
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
