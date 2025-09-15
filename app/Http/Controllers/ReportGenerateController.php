<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Models\InvoiceDatabase;
use App\Models\MasterSheet;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

class ReportGenerateController extends Controller
{
    // Report Generate for invoices within a date range
    #[NoReturn]
    public function invoiceReport(Request $request): void
    {
        // Validate the input dates
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        // Fetch invoice records within the date range
        $invoices = InvoiceDatabase::whereBetween('date', [$from_date, $to_date])->get();

        // Define the filename for the Excel file
        $filename = "po_" . $from_date . "_to_" . $to_date . ".xls";

        // Set headers for Excel file download
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open the output buffer
        $output = fopen('php://output', 'wb');

        // Write the header row
        $headers = [
            'Invoice No',
            'PO No',
            'Reference No',
            'Date',
            'Total Qty',
            'Total Amount',
            'Status',
        ];
        fputcsv($output, $headers, "\t");

        // Write data rows
        foreach ($invoices as $invoice) {

            // Fetch the total price from the "master" sheet based on the invoice number
            $masterRecord = MasterSheet::where('invoice_no', $invoice->invoice_no)->first();
            $total_price = $masterRecord ? $masterRecord->invoice_value : 'N/A';

            fputcsv($output, [
                $invoice->invoice_no ?? '-',
                $invoice->po_number ?? '-',
                $invoice->reference_no ?? '-',
                $invoice->date ?? '-',
                $invoice->no_of_items ?? '-',
                $total_price ?? '-',
                $invoice->status ?? '-',
            ], "\t");
        }

        // Close the output buffer
        fclose($output);
        exit;
    }

    // Report Generates for a pending list within a date range
    #[NoReturn]
    public function pendingListReport(Request $request): void
    {
        // Validate the input dates
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        // Fetch pending list records within the date range
        $pendingList = MasterSheet::whereBetween('mail_date', [$from_date, $to_date])
            ->where(function ($query) {
                $query->where('status', '!=', 'delivered')
                    ->orWhereNull('status');
            })
            ->get();


        // Define the filename for the Excel file
        $filename = "pending_list_" . $from_date . "_to_" . $to_date . ".xls";

        // Set headers for Excel file download
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open the output buffer
        $output = fopen('php://output', 'wb');

        // Write the header row
        $headers = [
            'PO No',
            'Reference No',
            'Mail Date',
            'Required Date',
            'Total Qty',
            'Total Amount',
            'Status',
        ];
        fputcsv($output, $headers, "\t");

        // Write data rows
        foreach ($pendingList as $invoice) {

            fputcsv($output, [
                $invoice->cust_ref ?? '-',
                $invoice->id ?? '-',
                $invoice->mail_date ?? '-',
                $invoice->required_date ?? '-',
                $invoice->no_of_items ?? '-',
                $invoice->invoice_value ?? '-',
                $invoice->status ?? '-',
            ], "\t");
        }

        // Close the output buffer
        fclose($output);
        exit;
    }

    //Report generates for a "master" sheet with date filters
    #[NoReturn]
    public function masterSheetReport(Request $request): void
    {
        // Validate the input dates
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        // Fetch master sheet records within the date range
        $masterSheet = MasterSheet::whereBetween('invoice_date', [$from_date, $to_date])->get();

        // Define the filename for the Excel file
        $filename = "master_sheet_" . $from_date . "_to_" . $to_date . ".xls";

        // Set headers for Excel file download
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open the output buffer
        $output = fopen('php://output', 'wb');

        // Write the header row
        $headers = [
            'our_ref#',
            'mail_date',
            'required_date',
            'created_by',
            'art_sent_date',
            'art_approved_date',
            'print_date',
            'invoice_date',
            'invoice_no',
            'cust_ref',
            'description',
            'dn',
            'dn_date',
            'pcs',
            'invoice_value',
            'create_date'
        ];
        fputcsv($output, $headers, "\t");

        // Write data rows
        foreach ($masterSheet as $master) {
            fputcsv($output, [
                $master->id ?? '-',
                $master->mail_date ?? '-',
                $master->required_date ?? '-',
                $master->created_by ?? '-',
                $master->art_sent_date ?? '-',
                $master->art_approved_date ?? '-',
                $master->print_date ?? '-',
                $master->invoice_date ?? '-',
                $master->invoice_no ?? '-',
                $master->cust_ref ?? '-',
                $master->description ?? '-',
                $master->dn ?? '-',
                $master->dn_date ?? '-',
                $master->pcs ?? '-',
                $master->invoice_value ?? '-',
                $master->created_at ?? '-',
            ], "\t");
        }

        // Close the output buffer
        fclose($output);
        exit;
    }

    // Report Generate for complete orders within a date range
    #[NoReturn]
    public function completeOrderReport(Request $request): void
    {
        // Validate the input dates
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        // Fetch complete orders records within the date range
        $completeOrders = InvoiceDatabase::whereBetween('date', [$from_date, $to_date])
            ->where('status', 'Complete')
            ->get();

        // Define the filename for the Excel file
        $filename = "complete_orders_" . $from_date . "_to_" . $to_date . ".xls";

        // Set headers for Excel file download
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open the output buffer
        $output = fopen('php://output', 'wb');

        // Write the header row
        $headers = [
            'Invoice No',
            'PO No',
            'Reference No',
            'Date',
            'Total Qty',
            'Total Amount',
            'Status',
        ];
        fputcsv($output, $headers, "\t");

        // Write data rows
        foreach ($completeOrders as $invoice) {
            // Fetch the total price from the "master" sheet based on the invoice number
            $masterRecord = MasterSheet::where('invoice_no', $invoice->invoice_no)->first();
            $total_price = $masterRecord ? $masterRecord->invoice_value : 'N/A';

            fputcsv($output, [
                $invoice->invoice_no ?? '-',
                $invoice->po_number ?? '-',
                $invoice->reference_no ?? '-',
                $invoice->date ?? '-',
                $invoice->no_of_items ?? '-',
                $total_price,
                $invoice->status ?? '-',
            ], "\t");
        }

        // Close the output buffer
        fclose($output);
        exit;
    }

    public function purchaseOrderReport(Request $request): View|Factory|Application
    {
        // Step 1: Validate input
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from_date = Carbon::parse($request->input('from_date'))->startOfDay();
        $to_date = Carbon::parse($request->input('to_date'))->endOfDay();

        // Step 2: Exchange rate - pick latest rate in range or latest available
        $exchangeRate = ExchangeRate::where('currency_from', 'USD')
            ->where('currency_to', 'LKR')
            ->first();

        $rate = $exchangeRate ? $exchangeRate->rate : 0;

        // Step 3: PO calculations
        $invoicedInRange = MasterSheet::whereNotNull('invoice_date')
            ->whereBetween('mail_date', [$from_date, $to_date])
            ->sum('invoice_value');

        $invoicedInRange2 = MasterSheet::whereNotNull('invoice_date')
            ->whereBetween('invoice_date', [$from_date, $to_date])
            ->whereBetween('mail_date', [$from_date, $to_date])
            ->sum('invoice_value');

        $notInvoicedInRange = MasterSheet::whereNull('invoice_date')
            ->whereBetween('mail_date', [$from_date, $to_date])
            ->sum('invoice_value');

        $totalBefore = MasterSheet::whereDate('mail_date', '<', $from_date)
            ->sum('invoice_value');

        $alreadyInvoicedBefore = MasterSheet::whereDate('mail_date', '<', $from_date)
            ->whereNotNull('invoice_date')
            ->where('invoice_date', '<', $from_date)
            ->sum('invoice_value');

        $carriedForward = $totalBefore - $alreadyInvoicedBefore;

        $invoicedFromPrevious = MasterSheet::whereDate('mail_date', '<', $from_date)
            ->whereBetween('invoice_date', [$from_date, $to_date])
            ->sum('invoice_value');

        $remainingFromPrevious = $carriedForward;

        $summary = [
            'invoiced_in_range' => $invoicedInRange,
            'invoiced_in_range2' => $invoicedInRange2,
            'not_invoiced_in_range' => $notInvoicedInRange,
            'carried_forward' => $carriedForward,
            'invoiced_from_previous' => $invoicedFromPrevious,
            'remaining_from_previous' => $remainingFromPrevious,
        ];

        if ($invoicedFromPrevious > $carriedForward) {
            Log::warning("Invoiced from previous months ($invoicedFromPrevious) exceeds carried forward ($carriedForward) — check data integrity.");
        }

        return view('summaryreport', compact('summary', 'from_date', 'to_date', 'rate'));
    }


    //Generate the sales report
    #[NoReturn]
    public function salesReport(Request $request): void
    {
        // Validate the input date range
        $request->validate([
            'from_date' => 'required|date', // from_date must be a valid date
            'to_date' => 'required|date|after_or_equal:from_date', // to_date must be a valid date and after from_date
        ]);

        // Get the from_date and to_date from the request
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        // Fetch invoice records within the date range
        $invoices = InvoiceDatabase::whereBetween('date', [$from_date, $to_date])->get();

        // Define the filename for the Excel file
        $filename = "sales_report_" . $from_date . "_to_" . $to_date . ".xls";

        // Set headers for Excel file download
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open the output buffer
        $output = fopen('php://output', 'wb');

        // Write the header row
        $headers = [
            'Invoice No',
            'PO No',
            'Reference No',
            'Date',
            'Customer Name',
            'Total Quantity',
            'Total Value',
            'Status',
        ];
        fputcsv($output, $headers, "\t");

        // Initialize totals for summary
        $total_sales_qty = 0;
        $total_sales_value = 0;

        // Write data rows
        foreach ($invoices as $invoice) {
            // Calculate total quantity and total value for the invoice using the mastersheet
            $masterRecord = MasterSheet::where('invoice_no', $invoice->invoice_no)->first();
            $total_qty = $invoice->no_of_items;
            $total_value = $masterRecord ? $masterRecord->invoice_value : 0;

            // Add to totals
            $total_sales_qty += $total_qty;
            $total_sales_value += $total_value;

            // Write invoice data to Excel
            fputcsv($output, [
                $invoice->invoice_no ?? '-',
                $invoice->po_number ?? '-',
                $invoice->reference_no ?? '-',
                $invoice->date ?? '-',
                $invoice->customer_name ?? '-',
                $total_qty ?? '0',
                number_format($total_value, 3) ?? '0.000', // Format value to 2 decimal places
                $invoice->status ?? '-',
            ], "\t");
        }

        // Write a blank row for separation
        fputcsv($output, [], "\t");

        // Write summary rows
        fputcsv($output, ['Summary'], "\t");
        fputcsv($output, ['Total Sales Quantity', $total_sales_qty], "\t");
        fputcsv($output, ['Total Sales Value', number_format($total_sales_value, 2)], "\t");

        // Close the output buffer
        fclose($output);
        exit;
    }
}
