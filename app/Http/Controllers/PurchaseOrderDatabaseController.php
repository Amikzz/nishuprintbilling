<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrderDatabase;
use App\Http\Requests\StorePurchaseOrderDatabaseRequest;
use App\Http\Requests\UpdatePurchaseOrderDatabaseRequest;

class PurchaseOrderDatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve search query
        $search = $request->input('search');

        // Filter the purchase orders if a search query is provided
        $purchaseOrders = PurchaseOrderDatabase::when($search, function ($query, $search) {
            $query->where('reference_no', 'like', "%{$search}%")
                ->orWhere('po_no', 'like', "%{$search}%")
                ->orWhere('item_code', 'like', "%{$search}%");
        })->paginate(10);

        // Return the view with the purchase orders and the search query
        return view('purchaseorders', compact('purchaseOrders', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

            // Loop through each item and save it to the database
            foreach ($validated['items'] as $item) {
                PurchaseOrderDatabase::create([
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

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error creating purchase order: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while creating the purchase order. Please try again.');
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
        //
    }
}
