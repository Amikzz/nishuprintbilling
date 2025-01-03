<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDatabase;
use App\Models\Items;
use App\Models\MasterSheet;
use Illuminate\Http\Request;

class MasterSheetController extends Controller
{
    //function to get all the details related to the master sheet
    public function getMasterSheet(Request $request)
    {
        $invoices = MasterSheet::all();
        $items = Items::all();

        return view('mastersheet', compact('invoices', 'items'));
    }

    //function to create a new master sheet
    public function createMasterSheet(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'mail_date' => 'required|date',
            'required_date' => 'required|date|after_or_equal:mail_date', // Ensure required date is after or equal to mail date
            'created_by' => 'required|string|max:255',
            'cust_ref' => 'required|string|max:255',
            'description' => 'nullable|string|max:255'
        ]);

        // Create the new MasterSheet record
        try {
            MasterSheet::create([
                'mail_date' => $validated['mail_date'],
                'required_date' => $validated['required_date'],
                'created_by' => $validated['created_by'],
                'cust_ref' => $validated['cust_ref'],
                'description' => $validated['description'],
                'status' => 'pending'
            ]);

            return redirect()->route('mastersheet')->with('success', 'Master Sheet created successfully');
        } catch (\Exception $e) {
            // Handle errors in case of failure
            return redirect()->route('mastersheet')->with('error', 'Failed to create Master Sheet. Please try again later.' . $e->getMessage());
        }
    }
}
