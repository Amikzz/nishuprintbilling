<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InvoiceDatabase;
use App\Models\Items;
use App\Models\MasterSheet;
use App\Models\PurchaseOrderDatabase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DeliveryNCreateController extends Controller
{
    public function createDeliveryNote(Request $request, $po_number): BinaryFileResponse|JsonResponse
    {
        $validated = $request->validate([
            'delivery_note_number' => 'required|string|max:255',
        ]);

        // Fetch the invoice details based on the invoice number from the URL
        $invoice = InvoiceDatabase::where('po_number', $po_number)->first();

        // If the invoice is not found, return an error response
        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        // Fetch the related purchase order using the po_number and reference_no from the invoice
        $purchaseOrder = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
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
            ->get();

        //get the number of items
        $item_count = count($purchaseOrderItems);

        // Fetch corresponding items from the Item table
        $purchaseOrderItemsDetails = $purchaseOrderItems->map(function ($orderItem) {
            $item = Items::where('item_code', $orderItem->item_code)->first();

            return (object)[
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

        // Create a delivery note number
        $invoice->delivery_note_no = $validated['delivery_note_number'];
        $invoice->save();

        $mastersheet = MasterSheet::where('cust_ref', $po_number)->first();
        $mastersheet->dn = $validated['delivery_note_number'];
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
        $pdf = PDF::loadView('deliverynote', [
            'date' => now(),
            'invoice' => $invoice,
            'pages' => $pages,
            'customer' => $customer,
            'delivery_note_no' => $delivery_note_no,
            'purchaseOrderItemsDetails' => $purchaseOrderItemsDetails,
            'item_count' => $item_count,
        ]);

        $pdfPath = storage_path("app/public/deliverynotes/DeliveryNote_$delivery_note_no.pdf");
        $pdf->save($pdfPath);

        Log::info('PDF saved to storage successfully', [
            'po_number' => $po_number,
            'delivery_note_number' => $validated['delivery_note_number'],
            'total_items' => $item_count,
            'path' => $pdfPath
        ]);

        return response()->file($pdfPath);
    }
}
