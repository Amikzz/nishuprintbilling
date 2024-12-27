<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDatabase;
use App\Models\MasterSheet;
use App\Models\PurchaseOrderDatabase;
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

    //Report generate for master sheet with date filters
    public function masterSheetReport(Request $request)
    {
        // Validate the input dates
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        // Fetch master sheet records within the date range
        $masterSheet = MasterSheet::whereBetween('created_at', [$from_date, $to_date])->get();

        // Define the filename for the Excel file
        $filename = "master_sheet_" . $from_date . "_to_" . $to_date . ".xls";

        // Set headers for Excel file download
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open the output buffer
        $output = fopen('php://output', 'w');

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
                $master->our_ref ?? '-',
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
    public function completeOrderReport(Request $request)
    {
        // Validate the input dates
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

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
        foreach ($completeOrders as $invoice) {
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

    // Report Generate for purchase orders related with the invoice number within a date range
    // Report Generate for purchase orders
    public function purchaseOrderReport(Request $request)
    {
        // Validate the input dates
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from_date = $request->from_date;
        $to_date = $request->to_date;

        // Fetch purchase orders within the date range
        $purchase_orders = InvoiceDatabase::whereBetween('date', [$from_date, $to_date])->get();

        // Define the filename for the Excel file
        $filename = "purchase_orders_" . $from_date . "_to_" . $to_date . ".xls";

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
            'Item Code',
            'Color No',
            'Color Name',
            'Size',
            'Style',
            'UPC No',
            'More 1',
            'More 2',
        ];
        fputcsv($output, $headers, "\t");

        // Write data rows
        foreach ($purchase_orders as $invoice) {
            $purchaseOrderItems = PurchaseOrderDatabase::where('po_no', $invoice->po_number)
                ->where('reference_no', $invoice->reference_no)
                ->get();

            foreach ($purchaseOrderItems as $order) {
                fputcsv($output, [
                    $invoice->invoice_no ?? '-',
                    $order->po_no ?? '-',
                    $order->reference_no ?? '-',
                    $invoice->date ?? '-',
                    $order->item_code ?? '-',
                    $order->color_no ?? '-',
                    $order->color_name ?? '-',
                    $order->size ?? '-',
                    $order->style ?? '-',
                    $order->upc_no ?? '-',
                    $order->more1 ?? '-',
                    $order->more2 ?? '-',
                ], "\t");
            }
        }

        // Close the output buffer
        fclose($output);
        exit;
    }

    //Generate the sales report
    public function salesReport(Request $request)
    {
        // Validate the input date range
        $validated = $request->validate([
            'from_date' => 'required|date', // from_date must be a valid date
            'to_date' => 'required|date|after_or_equal:from_date', // to_date must be a valid date and after from_date
        ]);

        // Get the from_date and to_date from the request
        $from_date = $request->from_date;
        $to_date = $request->to_date;

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
        $output = fopen('php://output', 'w');

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
