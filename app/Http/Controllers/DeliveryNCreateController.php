<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InvoiceDatabase;
use App\Models\Items;
use App\Models\MasterSheet;
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

        $invoiceno = $invoice->invoice_no;

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
        $customer = Customer::find($purchaseOrder->customer_id);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        // Fetch the related purchase orders and the corresponding items using the po_number and reference_no from the invoice
        $purchaseOrderItems = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
            ->where('reference_no', $invoice->reference_no)
            ->get();

        //get the number of items
        $item_count = count($purchaseOrderItems);

        // Fetch corresponding items from the Item table
        $purchaseOrderItemsDetails = $purchaseOrderItems->map(function ($orderItem) {
            $item = Items::where('item_code', $orderItem->item_code)->first();

            return (object) [
                'item_code' => $item ? $item->item_code : 'N/A',
                'item_name' => $item ? $item->name : 'Unknown Item',
                'sticker_size' => $item ? $item->description : 'N/A',
                'color_no' => $orderItem->color_no,
                'color' => $orderItem->color_name,
                'size' => $orderItem->size,
                'style' => $orderItem->style,
                'upc' => $orderItem->upc_no,
                'more' => $orderItem->more1,
                'po_qty' => $orderItem->po_qty,
                'unit_price' => $item ? $item->price : 0,
                'price' => $orderItem->price,
                'total' => $orderItem->quantity * ($item ? $item->price : 0)
            ];
        });

        // Create delivery note number
        $invoiceIdWithoutPrefix = str_replace('NC-24-25-', '', $invoiceId);
        $invoice->delivery_note_no = $invoiceIdWithoutPrefix;
        $invoice->save();

        $mastersheet = MasterSheet::where('invoice_no', $invoiceno)->first();
        $mastersheet->dn = $invoiceIdWithoutPrefix;
        $mastersheet->dn_date = now();
        $mastersheet->save();

        $delivery_note_no = $invoice->delivery_note_no;

        if ($purchaseOrderItems->isEmpty()) {
            return response()->json(['error' => 'No items found for this purchase order'], 404);
        }

        // Split items into chunks of 30 for pagination (similar to the invoice creation process)
        $itemsPerPage = 30;
        $pages = $purchaseOrderItemsDetails->chunk($itemsPerPage);

        // Generate the delivery note PDF with paginated items
        $pdf = Pdf::loadView('deliverynote', [
            'invoice' => $invoice,
            'pages' => $pages,
            'customer' => $customer,
            'delivery_note_no' => $delivery_note_no,
            'purchaseOrderItemsDetails' => $purchaseOrderItemsDetails,
            'item_count' => $item_count,

        ]);

        return $pdf->download($delivery_note_no. '.pdf');
    }
}
