<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceDatabase;
use App\Http\Requests\StoreInvoiceDatabaseRequest;
use App\Http\Requests\UpdateInvoiceDatabaseRequest;

class InvoiceDatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize query builder
        $query = InvoiceDatabase::query();

        // Check for search terms in the request and apply filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('invoice_no', 'like', '%' . $search . '%')
                    ->orWhere('reference_no', 'like', '%' . $search . '%')
                    ->orWhere('po_number', 'like', '%' . $search . '%');
            });
        }

        // Check for date range in the request
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != '') {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            // Apply date filter (assuming the `date` column in your database stores the invoice date)
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        // Paginate the results, 10 records per page
        $invoices = $query->paginate(10);

        // Return the results to the view
        return view('invoicedelivery', compact('invoices'));
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
    public function store(StoreInvoiceDatabaseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InvoiceDatabase $invoiceDatabase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoiceDatabase $invoiceDatabase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceDatabaseRequest $request, InvoiceDatabase $invoiceDatabase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceDatabase $invoiceDatabase)
    {
        //
    }
}
