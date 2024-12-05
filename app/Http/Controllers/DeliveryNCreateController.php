<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InvoiceDatabase;
use App\Models\Items;
use App\Models\PurchaseOrderDatabase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class DeliveryNCreateController extends Controller
{
    public function createDeliveryNote(Request $request, $invoiceId)
    {
        // Fetch the invoice details based on the invoice number from the URL
        $invoice = InvoiceDatabase::where('invoice_no', $invoiceId)->first();

        // If the invoice is not found, return an error response
        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Fetch the related purchase order using the po_number and reference_no from the invoice
        $purchaseOrder = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
            ->where('reference_no', $invoice->reference_no)
            ->first();

        if (!$purchaseOrder) {
            return response()->json(['error' => 'Purchase order not found'], 404);
        }

        // Fetch customer details based on customer_id from the purchase order
        $customer = Customer::find($purchaseOrder->customer_id);  // Assuming the customer_id is a foreign key in purchase_order_database

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Fetch the related purchase orders and the corresponding items using the po_number and reference_no from the invoice
        $purchaseOrderItems = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
            ->where('reference_no', $invoice->reference_no)
            ->get();

        // Fetch corresponding items from the Item table
        $purchaseOrderItemsDetails = $purchaseOrderItems->map(function ($orderItem) {
            // Get item data from the Items table using item_code (assuming it's the foreign key)
            $item = Items::where('item_code', $orderItem->item_code)->first();

            // Return a merged object of both item and order details
            return (object) [
                'item_code' => $item ? $item->item_code : 'N/A', // Handle if item not found
                'item_name' => $item ? $item->name : 'Unknown Item', // Handle if item not found
                'color_no' => $orderItem->color_no,
                'color' => $orderItem->color_name,
                'size' => $orderItem->size,
                'po_qty' => $orderItem->po_qty,
                'unit_price' => $item ? $item->price : 0, // Handle if item not found
                'price' => $orderItem->price,
                'total' => $orderItem->quantity * ($item ? $item->price : 0)
            ];
        });

        //create delivery note number
        $invoiceIdWithoutPrefix = str_replace('INV-', '', $invoiceId);
        $invoice->delivery_note_no = 'DN-' . $invoiceIdWithoutPrefix;
        $invoice->save();

        $delivery_note_no = $invoice->delivery_note_no;

        if ($purchaseOrderItems->isEmpty()) {
            return response()->json(['error' => 'No items found for this purchase order'], 404);
        }

        // Generate the invoice PDF
        $pdf = Pdf::loadView('deliverynote', compact('invoice', 'purchaseOrderItemsDetails', 'customer', 'delivery_note_no'));

        // Ensure the storage directory exists
        $filePath = storage_path('app/public/deliverynotes/' . $invoice->delivery_note_no . '.pdf');
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true); // Create the directory if it doesn't exist
        }

        // Save the PDF file
        file_put_contents($filePath, $pdf->output());

        // Flash a success message to the session
        session()->flash('success', 'Delivery Note created successfully!');

        // Redirect to the invoice index page with the success message
        return redirect()->route('invoice-databases.index');
    }
}
