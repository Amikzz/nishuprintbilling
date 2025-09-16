<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InvoiceDatabase;
use App\Models\Items;
use App\Models\PurchaseOrderDatabase;
use App\Models\ReturnDatabase;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ReturnController extends Controller
{
    // Search Invoice Function
    public function searchInvoice(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'delivery_note_no' => 'required|string'
            ]);

            // Fetch the invoice details
            $invoice = InvoiceDatabase::where('delivery_note_no', $request->input('delivery_note_no'))->first();
            if (!$invoice) {
                return response()->json(['message' => 'Delivery note not found'], 404);
            }

            // Fetch PO details related to the invoice
            $poDetails = PurchaseOrderDatabase::where('po_no', $invoice->po_number)->get();
            if ($poDetails->isEmpty()) {
                return response()->json(['message' => 'No Purchase Orders found for this invoice'], 404);
            }

            // Save data to return_database
            foreach ($poDetails as $po) {
                ReturnDatabase::create([
                    'invoice_number' => $invoice->invoice_no,
                    'delivery_note_no' => $request->input('delivery_note_no'),
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

            // Flash success message and redirect
            return redirect()->route('return.page')->with('success', 'Invoice found and items added to return database');

        } catch (ValidationException $e) {
            // Handle validation errors
            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (QueryException $e) {
            // Handle database errors
            return redirect()->back()->with('error', 'A database error occurred. Please try again later.' . $e->getMessage());

        } catch (Exception) {
            // Handle any other exceptions
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
        }

    }

    public function viewUpdated(Request $request): View|Application|Factory
    {
        $request->validate([
            'delivery_note_no' => 'required|string'
        ]);

        // get all the items of the return database related to the invoice number
        $returnItems = ReturnDatabase::where('delivery_note_no', $request->input('delivery_note_no'))->get();
        $d_note_no = $request->input('delivery_note_no');

        return view('updatedrecord', compact('returnItems', 'd_note_no'));
    }

    /**
     * Update a specific return item.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'color_name' => 'nullable|string|max:255',
            'color_no' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:50',
            'style' => 'nullable|string|max:100',
            'upc_no' => 'nullable|string|max:100',
            'po_qty' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'more1' => 'nullable|string|max:255',
            'more2' => 'nullable|string|max:255',
        ]);

        // Find the return item by ID
        $returnItem = ReturnDatabase::findOrFail($id);

        // Update the return item with the validated data
        $returnItem->update([
            'color_name' => $validated['color_name'],
            'color_no' => $validated['color_no'],
            'size' => $validated['size'],
            'style' => $validated['style'],
            'upc_no' => $validated['upc_no'],
            'po_qty' => $validated['po_qty'],
            'price' => $validated['price'],
            'more1' => $validated['more1'],
            'more2' => $validated['more2'],
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Item updated successfully');
    }

    /**
     * Delete a specific return item.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        // Find the return item by ID
        $returnItem = ReturnDatabase::findOrFail($id);

        // Delete the return item
        $returnItem->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Item deleted successfully');
    }

    // Delete Item in Return Database
    public function deleteItem($id): RedirectResponse
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


    // Create Delivery Note
    public function createDeliveryNote(Request $request, $d_note_no): Response|JsonResponse
    {
        $request->validate([
            'new_dnote_no' => 'required|string',
            'type' => 'required|string'
        ]);

        $returnItems = ReturnDatabase::where('delivery_note_no', $d_note_no)->get();

        if ($returnItems->isEmpty()) {
            return response()->json(['message' => 'No items found for the delivery note'], 404);
        }

        $customerID = 1; // Placeholder for customer ID
        $customer = Customer::find($customerID);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // Fetch corresponding items from the Item table
        $purchaseOrderItemsDetails = $returnItems->map(function ($orderItem) {
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

        if ($purchaseOrderItemsDetails->isEmpty()) {
            return response()->json(['error' => 'No items found for this purchase order'], 404);
        }

        $newDeliveryNoteNo = $request->input('new_dnote_no');

        //enter the new delivery note number in the return database
        foreach ($returnItems as $item) {
            $item->update([
                'new_dnote_no' => $newDeliveryNoteNo
            ]);
        }

        // Split items into chunks of 30 for pagination (similar to the invoice creation process)
        $itemsPerPage = 30;
        $pages = $purchaseOrderItemsDetails->chunk($itemsPerPage);

        // Generate the delivery note PDF with paginated items
        $pdf = Pdf::loadView('deliverynotereturn', [
            'type' => $request->input('type'),
            'date' => now()->format('Y-m-d'),
            'po_no' => $returnItems->first()->po_no,
            'pages' => $pages,
            'customer' => $customer,
            'delivery_note_no' => $newDeliveryNoteNo,
            'purchaseOrderItemsDetails' => $purchaseOrderItemsDetails,
        ]);

        return $pdf->download($newDeliveryNoteNo . '.pdf');
    }
}
