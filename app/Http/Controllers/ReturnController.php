<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDatabase;
use App\Models\PurchaseOrderDatabase;
use App\Models\ReturnDatabase;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    // Search Invoice Function
    public function searchInvoice(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string'
        ]);

        // Fetch the invoice details
        $invoice = InvoiceDatabase::where('invoice_no', $request->invoice_number)->first();
        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        // Fetch PO details related to the invoice
        $poDetails = PurchaseOrderDatabase::where('po_no', $invoice->po_number)->get();

        // Save data to return_database
        $returnData = [];
        foreach ($poDetails as $po) {
            $returnData[] = ReturnDatabase::create([
                'invoice_number' => $request->invoice_number,
                'po_no' => $po->po_no,
                'item_code' => $po->item_code,
                'color_name' => $po->color_name,
                'color_no' => $po->color_no,
                'size' => $po->size,
                'style' => $po->style,
                'upc_no' => $po->upc_no,
                'po_qty' => $po->po_qty,
                'price' => $po->price,
                'more1' => $po->more1,
                'more2' => $po->more2,
            ]);
        }

       //success session flash message
        return redirect()->route('return.page')->with('success', 'Invoice found and items added to return database');

    }

    public function viewUpdated(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string'
        ]);

        // get the all the items of the return database related to the invoice number
        $returnItems = ReturnDatabase::where('invoice_number', $request->invoice_number)->get();

        return view('updatedrecord', compact('returnItems'));
    }


    // Update Item in Return Database
    public function updateItem(Request $request, $id)
    {
        $request->validate([
            'field' => 'required|string',
            'value' => 'required'
        ]);

        // Find the item in return_database
        $returnItem = ReturnDatabase::find($id);
        if (!$returnItem) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        // Update the specified field
        $returnItem->update([
            $request->field => $request->value
        ]);

        return response()->json(['message' => 'Item updated successfully']);
    }

    // Delete Item in Return Database
    public function deleteItem($id)
    {
        // Find the item by its ID
        $item = ReturnDatabase::find($id);

        // Check if the item exists
        if (!$item) {
            return back()->with('error', 'Item not found');
        }

        // Delete the item
        $item->delete();

        return redirect()->route('view.updated.records')->with('success', 'Item deleted successfully');
    }


    // Create Delivery Note (Placeholder)
    public function createDeliveryNote(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|string'
        ]);

        // Logic to create a delivery note (as per your requirement)
        return response()->json(['message' => 'Delivery Note created successfully']);
    }
}
