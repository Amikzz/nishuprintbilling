<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice->invoice_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 5px;
            page-break-after: always;
        }
        .header {
            margin-bottom: 20px;
        }
        img {
            max-width: 80px;
            margin-right: 10px;
        }
        .details {
            width: 40%;
            float: right;
            text-align: right;
            font-size: 9px;
            line-height: 1.1;
        }
        .image {
            width: 40%;
            float: left;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        .invoice-details {
            margin-bottom: 10px;
        }
        .invoice-details table {
            width: 70%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .invoice-details th, .invoice-details td {
            padding: 3px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .invoice-details th {
            background-color: #f4f4f4;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-top: 5px;
        }
        .items-table th, .items-table td {
            padding: 3px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .items-table th {
            background-color: #f4f4f4;
        }
        .totals {
            margin-top: 10px;
            font-size: 10px;
            text-align: right;
        }
        .sign-space {
            margin-top: 30px;
            font-size: 10px;
        }
        .signatures {
            display: block;
            width: 100%;
            justify-content: space-between;
            margin-top: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .signature-box-left {
            width: 30%;
            float: left;
            text-align: left;
        }
        .signature-box-right {
            width: 30%;
            float: right;
            text-align: left;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

@php
    $itemsPerPage = 30;
    $totalPages = ceil($purchaseOrderItemsDetails->count() / $itemsPerPage);
    $grandTotal = $purchaseOrderItemsDetails->sum('price');
    $convertedTotal = $grandTotal * $exchangeRate;
@endphp

@for ($page = 1; $page <= $totalPages; $page++)
    @php
        $start = ($page - 1) * $itemsPerPage;
        $end = min($start + $itemsPerPage, $purchaseOrderItemsDetails->count());
        $pageItems = $purchaseOrderItemsDetails->slice($start, $end - $start);
        $pageTotal = $pageItems->sum('price');
        $pageConvertedTotal = $pageTotal * $exchangeRate;
    @endphp

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="details">
                <p><strong>Nisu Creations (Pvt) Ltd</strong></p>
                <p>493/5A, Makola North, Makola</p>
                <p>Tel: 0094 1 4063878 / 0094 1 292656</p>
                <p>Email: nisucreations@gmail.com</p>
            </div>
            <div class="image">
                <img src="{{ public_path('images/logo.jpg') }}" alt="Logo">
            </div>
        </div>

        <!-- Invoice Heading -->
        <div class="title">
            Invoice
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <table>
                <tr>
                    <th>Invoice Number</th>
                    <td>{{ $invoice->invoice_no }}</td>
                </tr>
                <tr>
                    <th>Invoice Date</th>
                    <td>{{ \Carbon\Carbon::parse($invoice->date)->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <th>Your PO Number</th>
                    <td>{{ $invoice->po_number }}</td>
                </tr>
                <tr>
                    <th>Customer</th>
                    <td>{{ $customer->name }}</td>
                </tr>
                <tr>
                    <th>Customer Address</th>
                    <td>{{ $customer->address }}</td>
                </tr>
                <tr>
                    <th>Exchange Rate</th>
                    <td>{{ number_format($exchangeRate, 3) }}</td>
                </tr>
            </table>
        </div>

        <!-- Items Table -->
        <div class="items">
            <table class="items-table">
                <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($pageItems as $item)
                    <tr>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->color ?? '-' }}</td>
                        <td>{{ $item->size ?? '-' }}</td>
                        <td>{{ $item->po_qty }}</td>
                        <td>{{ number_format($item->unit_price, 3) }}</td>
                        <td>{{ number_format($item->price, 3) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="totals">
            <p>Page Total: ${{ number_format($pageTotal, 4) }}</p>
            <p>Grand Total: ${{ number_format($grandTotal, 4) }}</p>
            <p>Exchange rate: Rs.{{number_format($exchangeRate, 4)}}</p>
            <p><b>Converted Grand Total: Rs. {{ number_format($convertedTotal, 5) }}</b></p>
        </div>

        <!-- Footer Section -->
        <div class="sign-space">
            <p>Customer's Signature: ____________________________________________</p>
            <div class="signatures">
                <div class="signature-box-left">
                    <p>Checked by</p>
                    <p>__________________________</p>
                </div>
                <div class="signature-box-right">
                    <p>Approved by</p>
                    <p>__________________________</p>
                </div>
            </div>
        </div>
    </div>

    @if (($page+1) < $totalPages)
        <div class="page-break"></div>
    @endif
@endfor

</body>
</html>
