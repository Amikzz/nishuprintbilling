<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\MasterSheet;
use Illuminate\Http\Request;

class UrgentController extends Controller
{
    public function getMasterSheet(Request $request)
    {
        // Fetch invoices, prioritizing urgent ones first (assuming `status` field indicates if it's urgent)
        $invoices = MasterSheet::whereBetween('required_date', [now()->subDay(), now()->addDays(3)]) // Filter by required date between today and 3 days from today
        ->whereNotIn('status', ['delivered', 'completed'])  // Exclude delivered and completed statuses
        ->orderBy('id', 'desc')                             // Then order by ID in descending order
        ->get();

        $items = Items::all();

        return view('urgent', compact('invoices', 'items'));
    }
}
