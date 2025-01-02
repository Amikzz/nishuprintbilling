<?php

namespace App\Http\Controllers;

use App\Models\Edits;
use App\Models\InvoiceDatabase;
use App\Models\Items;
use App\Models\MasterSheet;
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
        $purchaseOrders = PurchaseOrderDatabase::with('items') // Assuming 'item' relationship exists
        ->when($search, function ($query, $search) {
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
            ->paginate(20); // Paginate results

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
                'date' => 'required|date',
                'purchase_order_number' => 'required|string|max:255|exists:master_sheet,cust_ref',
                'invoice_number' => 'required|integer|min:0',
                'items' => 'required|array|min:1',
                'items.*.name' => 'required|string|max:255',
                'items.*.color' => 'nullable|string|max:255',
                'items.*.color_number' => 'nullable|string|max:255',
                'items.*.size' => 'nullable|string|max:255',
                'items.*.style' => 'nullable|string|max:255',
                'items.*.upc' => 'nullable|string|max:255',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.more1' => 'nullable|string|max:255',
                'items.*.more2' => 'nullable|string|max:255',
            ]);

            // Begin a transaction
            DB::beginTransaction();

            $id = MasterSheet::where('cust_ref', $validated['purchase_order_number'])->first()->id;

            // Loop through each item and save it to the database
            foreach ($validated['items'] as $item) {
                // Create the purchase order
                $purchaseOrder = PurchaseOrderDatabase::create([
                    'date' => $validated['date'],                    // Current date
                    'reference_no' => $id,                           // Reference number
                    'po_no' => $validated['purchase_order_number'],  // Purchase order number
                    'item_code' => $item['name'],                    // Item name/code
                    'color_name' => $item['color'] ?? null,          // Color name
                    'color_no' => $item['color_number'] ?? null,     // Color number
                    'size' => $item['size'] ?? null,                 // Size
                    'style' => $item['style'] ?? null,               // Style
                    'upc_no' => $item['upc'] ?? null,                // UPC
                    'po_qty' => $item['quantity'],                   // Quantity
                    'price' => $item['price'],                       // Price
                    'customer_id' => 1,                              // Placeholder for customer ID
                    'more1' => $item['more1'] ?? null,               // Additional field 1
                    'more2' => $item['more2'] ?? null,               // Additional field 2
                ]);
            }

            // Create an invoice record for the purchase order
            // You can customize the invoice number generation logic here
            $invoiceNo = 'NC-' . '24-25' . '-' . $validated['invoice_number']; // Updated invoice number logic

            $masterSheet = MasterSheet::where('cust_ref', $validated['purchase_order_number'])->first();
            $masterSheet->invoice_no = $invoiceNo;
            $masterSheet->invoice_date = $validated['date'];
            $masterSheet->pcs = count($validated['items']);
            $masterSheet->invoice_value = array_sum(array_column($validated['items'], 'price'));
            $masterSheet->save();

            InvoiceDatabase::create([
                'date' => $validated['date'],                                     // Current date
                'invoice_no' => $invoiceNo,                          // Generated invoice number
                'customer_id' => 1,                                  // Placeholder for customer ID
                'po_number' => $validated['purchase_order_number'],  // PO Number (foreign key)
                'reference_no' => $id,                               // Reference number (foreign key)
                'no_of_items' => count($validated['items']),         // Number of items
                'status' => 'Pending',                               // Initial status
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
    public function edit($id)
    {
        // Fetch the invoice data by its ID
        $invoice = InvoiceDatabase::findOrFail($id);

        // Fetch the associated purchase order details based on PO number and Reference No
        $purchaseOrder = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
            ->where('reference_no', $invoice->reference_no)
            ->get();

        // Map through the purchase order items and gather additional information about the items
        $purchaseOrderItemsDetails = $purchaseOrder->map(function ($orderItem) {
            // Fetch item details using the item code
            $item = Items::where('item_code', $orderItem->item_code)->first();

            // Calculate the unit price for the item
            $unit_price = ($orderItem->price) / $orderItem->po_qty;

            return [
                'id' => $orderItem->id,
                'item_code' => $item ? $item->item_code : 'N/A',
                'item_name' => $item ? $item->name : 'Unknown Item',
                'color_no' => $orderItem->color_no,
                'color' => $orderItem->color_name,
                'size' => $orderItem->size,
                'style' => $orderItem->style,
                'upc' => $orderItem->upc_no,
                'po_qty' => $orderItem->po_qty,
                'unit_price' => $unit_price,
                'price' => $orderItem->price,
                'more1' => $orderItem->more1,
                'more2' => $orderItem->more2,
            ];
        });

        // Pass the data to the edit view
        return view('invoiceedit', [
            'invoice' => $invoice,
            'items' => $purchaseOrderItemsDetails,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $invoiceId)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'invoice_no' => 'required|string|max:255',
            'reference_no' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.item_code' => 'required|string|max:255',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.color' => 'nullable|string|max:255',
            'items.*.color_number' => 'nullable|string|max:255',
            'items.*.size' => 'nullable|string|max:255',
            'items.*.style' => 'nullable|string|max:255',
            'items.*.upc' => 'nullable|string|max:255',
            'items.*.more1' => 'nullable|string|max:255',
            'items.*.po_qty' => 'required|integer|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        //Update the edits table
        $edit = new Edits();
        $edit->date = Date::now();
        $edit->invoice_no = $validatedData['invoice_no'];
        $edit->reference_no = $validatedData['reference_no'];

        // Find the invoice by ID
        $invoice = InvoiceDatabase::findOrFail($invoiceId);

        // Update invoice information (invoice_no, reference_no, etc.)
        $invoice->invoice_no = $validatedData['invoice_no'];
        $invoice->reference_no = $validatedData['reference_no'];
        $invoice->save();

        // Loop through the items and update each one
        foreach ($validatedData['items'] as $index => $itemData) {
            // Find the corresponding PurchaseOrderItem using the po_number from the invoice
            $item = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
                ->where('id', $itemData['id'])
                ->first();

            if ($item) {
                $descriptionUpdate = "Updated item: {$itemData['item_name']} - Color: {$itemData['color']} - Color_No: {$itemData['color_number']} - Size: {$itemData['size']} - Quantity: {$itemData['po_qty']}";

                // Update the edits table
                $edit->description = $descriptionUpdate;

                // Update item details
                $item->color_name = $itemData['color'];
                $item->color_no = $itemData['color_number'];
                $item->size = $itemData['size'];
                $item->style = $itemData['style'];
                $item->upc_no = $itemData['upc'];
                $item->more1 = $itemData['more1'];
                $item->po_qty = $itemData['po_qty'];

                // Recalculate price based on quantity and unit price
                $item->price = $itemData['po_qty'] * $itemData['unit_price'];

                // Save the updated item
                $item->save();
            }
            $edit->save();
        }

        // Redirect back with a success message
        return redirect()->route('invoice-databases.index', $invoiceId)->with('success', 'Invoice updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrderDatabase $purchaseOrderDatabase)
    {
        //
    }

    public function export()
    {
        // Fetch data from the purchase orders table
        $purchaseOrders = DB::table('purchase_orders')->get();

        // Define the headers for the file download
        $filename = "purchase_orders_" . now()->format('Ymd_His') . ".csv";

        // Create an output buffer
        $output = fopen('php://output', 'w');

        // Set headers for the Excel file download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Define the CSV header row
        fputcsv($output, [
            'Date',
            'Reference No',
            'PO No',
            'Item Code',
            'Color No',
            'Color Name',
            'Size',
            'Style',
            'UPC No',
            'Quantity',
            'Price',
            'Status',
            'More 1',
            'More 2',
        ]);

        // Write each purchase order record to the file
        foreach ($purchaseOrders as $order) {
            fputcsv($output, [
                $order->date,
                $order->reference_no,
                $order->po_no,
                $order->item_code,
                $order->color_no ?? '-',
                $order->color_name ?? '-',
                $order->size ?? '-',
                $order->style ?? '-',
                $order->upc_no ?? '-',
                $order->po_qty,
                $order->price,
                $order->status,
                $order->more1 ?? '-',
                $order->more2 ?? '-',
            ]);
        }

        // Close the output buffer
        fclose($output);
        exit;
    }

}
