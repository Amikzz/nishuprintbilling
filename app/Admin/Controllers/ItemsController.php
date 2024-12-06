<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Items;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Layout\Content;

class ItemsController extends Controller
{
    /**
     * Display a paginated list of items.
     *
     * @param Request $request
     * @return Content
     */
    public function index(Request $request)
    {
        // Retrieve paginated items (10 items per page by default)
        $items = Items::paginate(10);

        return Admin::content(function (Content $content) use ($items) {
            $content->header('Items');
            $content->description('List of all items');

            $content->body(view('admin.itemindex', ['items' => $items]));
        });
    }

    public function store(Request $request)
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
        } catch (\Exception $e) {
            // Handle error and return message
            return redirect()->back();
        }
    }

}
