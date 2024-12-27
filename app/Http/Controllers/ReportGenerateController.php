<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDatabase;
use App\Models\MasterSheet;
use Illuminate\Http\Request;

class ReportGenerateController extends Controller
{
    // Report Generate for invoices within a date range
    public function invoiceReport(Request $request)
    {
        // Validate the input dates
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

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
        $output = fopen('php://output', 'w');

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

            // Fetch the total price from the master sheet based on the invoice number
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

    // Report Generate for pending list within a date range
    public function pendingListReport(Request $request)
    {
        // Validate the input dates
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        // Fetch pending list records within the date range
        $pendingList = InvoiceDatabase::whereBetween('date', [$from_date, $to_date])
            ->where('status', 'Pending')
            ->get();

        // Define the filename for the Excel file
        $filename = "pending_list_" . $from_date . "_to_" . $to_date . ".xls";

        // Set headers for Excel file download
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open the output buffer
        $output = fopen('php://output', 'w');

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
        foreach ($pendingList as $invoice) {
            // Fetch the total price from the master sheet based on the invoice number
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

}
