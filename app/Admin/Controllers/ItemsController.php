<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Items;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Layout\Content;

class ItemsController extends Controller
{
    /**
     * Display a paginated list of items.
     *
     * @return Content
     */
    public function index(): Content
    {
        // Retrieve paginated items (10 items per page by default)
        $items = Items::paginate(10);

        return Admin::content(static function (Content $content) use ($items) {
            $content->header('Items');
            $content->description('List of all items');

            $content->body(view('admin.itemindex', ['items' => $items]));
        });
    }

    public function store(Request $request): RedirectResponse
    {
        // Validate the request data
        $validatedData = $request->validate([
            'item_code' => 'required|string|max:255|unique:items,item_code',  // Validate item code to be unique
            'name' => 'required|string|max:255',  // Validate item name
            'description' => 'nullable|string|max:1000',  // Description is optional but can be long
            'price' => 'required|numeric|min:0',  // Price must be a number and cannot be negative
        ]);

        try {
            // Create a new item using the validated data
            $item = new Items();  // Ensure the model is referenced correctly (Item model)
            $item->item_code = $validatedData['item_code'];
            $item->name = $validatedData['name'];
            $item->description = $validatedData['description'] ?? null;  // Default to null if no description
            $item->price = $validatedData['price'];
            $item->save();  // Save the item to the database

            // Flash a success message
            return redirect()->route('admin.items.index');
        } catch (Exception) {
            // Handle error and return a message
            return redirect()->back();
        }
    }

    public function update(Request $request, $item_code): RedirectResponse
    {
        // Validate the request
        $validatedData = $request->validate([
            'item_code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
        ]);

        // Find the item by item_code
        $item = Items::where('item_code', $item_code)->first();

        if (!$item) {
            // Handle case where the item is not found
            return redirect()->route('admin.items');
        }

        // Manually update the fields
        $item->name = $validatedData['name'];
        $item->description = $validatedData['description'];
        $item->price = $validatedData['price'];

        // Save the updated item
        $item->save();

        // Redirect to the item list
        return redirect()->route('admin.items');
    }


    public function destroy($item_code): RedirectResponse
    {
        // Directly delete the item by item_code
        Items::where('item_code', $item_code)->delete();

        return redirect()->route('admin.items');

    }
}
