<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDatabase;
use App\Models\Items;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderDatabase;
use App\Http\Requests\StorePurchaseOrderDatabaseRequest;
use App\Http\Requests\UpdatePurchaseOrderDatabaseRequest;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Nette\Utils\Random;

class PurchaseOrderDatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve search query and date range filters
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Build the query
        $purchaseOrders = PurchaseOrderDatabase::when($search, function ($query, $search) {
            $query->where('reference_no', 'like', "%{$search}%")
                ->orWhere('po_no', 'like', "%{$search}%")
                ->orWhere('item_code', 'like', "%{$search}%");
        })
            ->when($startDate, function ($query, $startDate) {
                $query->whereDate('date', '>=', $startDate); // Filter by start date
            })
            ->when($endDate, function ($query, $endDate) {
                $query->whereDate('date', '<=', $endDate); // Filter by end date
            })
            ->paginate(10); // Paginate results

        // Return the view with purchase orders and the search/query parameters
        return view('purchaseorders', compact('purchaseOrders', 'search', 'startDate', 'endDate'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Items::all(); // Fetch all items from the database
        return view('welcome', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming request data
            $validated = $request->validate([
                'reference_number' => 'required|string|max:255',
                'purchase_order_number' => 'required|string|max:255',
                'items' => 'required|array|min:1',
                'items.*.name' => 'required|string|max:255',
                'items.*.color' => 'nullable|string|max:255',
                'items.*.color_number' => 'nullable|string|max:255',
                'items.*.size' => 'nullable|string|max:255',
                'items.*.style' => 'nullable|string|max:255',
                'items.*.upc' => 'nullable|string|max:255',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
            ]);

            // Begin a transaction
            DB::beginTransaction();

            // Loop through each item and save it to the database
            foreach ($validated['items'] as $item) {
                // Create the purchase order
                $purchaseOrder = PurchaseOrderDatabase::create([
                    'date' => now(),                                 // Current date
                    'reference_no' => $validated['reference_number'], // Reference number
                    'po_no' => $validated['purchase_order_number'],   // Purchase order number
                    'item_code' => $item['name'],                    // Item name/code
                    'color_name' => $item['color'] ?? null,          // Color name
                    'color_no' => $item['color_number'] ?? null,     // Color number
                    'size' => $item['size'] ?? null,                 // Size
                    'style' => $item['style'] ?? null,               // Style
                    'upc_no' => $item['upc'] ?? null,                // UPC
                    'po_qty' => $item['quantity'],                   // Quantity
                    'price' => $item['price'],                       // Price
                    'customer_id' => 1,                              // Placeholder for customer ID
                ]);
            }

            // Create an invoice record for the purchase order
            // You can customize the invoice number generation logic here
            $randomNumber = rand(1000, 9999);  // Generate a random 4-digit number
            $invoiceNo = 'NC-' . '24-25' . '-' . $randomNumber . '-' . $validated['reference_number']; // Updated invoice number logic

            InvoiceDatabase::create([
                'date' => now(),                                     // Current date
                'invoice_no' => $invoiceNo,                          // Generated invoice number
                'customer_id' => 1,                                  // Placeholder for customer ID
                'po_number' => $validated['purchase_order_number'],  // PO Number (foreign key)
                'reference_no' => $validated['reference_number'],    // Reference number (foreign key)
                'no_of_items' => count($validated['items']),         // Number of items
            ]);

            // Commit the transaction if everything is successful
            DB::commit();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Purchase order and invoice created successfully.');

        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Log the error for debugging
            \Log::error('Error creating purchase order and invoice: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while creating the purchase order and invoice. Please try again.' .$e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrderDatabase $purchaseOrderDatabase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseOrderDatabaseRequest $request, PurchaseOrderDatabase $purchaseOrderDatabase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrderDatabase $purchaseOrderDatabase)
    {
        try {
            // Delete the purchase order
            $purchaseOrderDatabase->delete();

            // Flash a success message
            session()->flash('success', 'Purchase order deleted successfully.');
        } catch (\Exception $e) {
            // Flash an error message if something goes wrong
            session()->flash('error', 'An error occurred while trying to delete the purchase order.');
        }

        // Redirect back to the previous page
        return redirect()->back();
    }

    /**
     * Update status of the purchase order for artwork need
     */
    public function artworkNeed($id)
    {
        try {
            // Find the purchase order by ID
            $purchaseOrder = PurchaseOrderDatabase::findOrFail($id);

            // Check if the current status is 'pending'
            if ($purchaseOrder->status === 'Pending') {
                // Update the status to 'artwork_needed'
                $purchaseOrder->status = 'Artwork_needed';
                $purchaseOrder->save();

                // Flash a success message
                session()->flash('success', 'Purchase order status updated to artwork needed.');
                return redirect()->back();
            }elseif ($purchaseOrder->status === 'Artwork_needed') {
                $purchaseOrder->status = 'Artwork_sent';
                $purchaseOrder->save();
                session()->flash('success', 'Purchase order status updated to artwork sent.');
            }
            return redirect()->back();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Flash an error message if the purchase order is not found
            session()->flash('error', 'Purchase order not found.');
            return redirect()->back();
        } catch (\Exception $e) {
            // Flash a general error message for any other exceptions
            session()->flash('error', 'An error occurred while updating the purchase order status.');
            return redirect()->back();
        }
    }

    public function artworkProduction($id)
    {
        try {
            // Find the purchase order by ID
            $purchaseOrder = PurchaseOrderDatabase::findOrFail($id);

            // Check if the current status is 'Artwork_sent'
            if ($purchaseOrder->status === 'Artwork_sent') {
                // Update the status to 'Artwork_approved'
                $purchaseOrder->status = 'Artwork_approved';
                $purchaseOrder->save();

                // Flash a success message
                session()->flash('success', 'Purchase order status updated to Artwork Approved.');
            } else {
                // Flash an error message if the status is not 'Artwork_sent'
                session()->flash('error', 'Purchase order status is not Artwork Sent and cannot be updated.');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Flash an error message if the purchase order is not found
            session()->flash('error', 'Purchase order not found.');
        } catch (\Exception $e) {
            // Flash a general error message for any other exceptions
            session()->flash('error', 'An error occurred while updating the purchase order status.');
        }

        // Redirect back to the previous page
        return redirect()->back();
    }

}
