<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\MasterSheet;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class UrgentController extends Controller
{
    public function getMasterSheet(): View|Application|Factory
    {
        // Fetch invoices with a required date less than 3 days from today
        // Exclude completed and delivered statuses, but include null status
        $invoices = MasterSheet::whereDate('required_date', '<', now()->addDays(3))  // Filter by the required date less than 3 days from today
        ->where(function ($query) {
            $query->whereNotIn('status', ['delivered', 'completed'])  // Exclude delivered and completed statuses
            ->orWhereNull('status');                           // Include null status
        })
            ->orderBy('id', 'desc')                                     // Order by ID in descending order
            ->get();

        // Get all items
        $items = Items::all();

        return view('urgent', compact('invoices', 'items'));
    }
}
