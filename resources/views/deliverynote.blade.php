<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Note - {{ $delivery_note_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 18px;
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
            font-size: 13px;
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
            margin-bottom: 5px;
        }
        .invoice-details table {
            width: 70%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .invoice-details th, .invoice-details td {
            padding: 3px;
            text-align: left;
            border: 1px solid black; /* Changed to black */
        }
        .invoice-details th {
            background-color: #f4f4f4;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-top: 5px;
        }
        .items-table th, .items-table td {
            padding: 3px;
            text-align: left;
            border: 1px solid black; /* Changed to black */
        }
        .items-table th {
            background-color: #f4f4f4;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .page-break {
            page-break-after: auto;
        }
        .sign-space {
            margin-top: 30px;
            font-size: 10px;
        }
        .signatures {
            display: block;
            width: 100%;
            justify-content: space-between;
            margin-top: 15px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .signature-box-left {
            width: 30%;
            float: left;
            text-align: left;
        }
        .signature-box-right {
            width: 25%;
            float: right;
            text-align: left;
        }
        .signature-box-center {
            width: 40%;
            float: right;
            text-align: left;
        }
    </style>
</head>
<body>

@php
    $itemsPerPage = 30;
    $totalPages = ceil($purchaseOrderItemsDetails->count() / $itemsPerPage);
@endphp

@for ($page = 1; $page <= $totalPages; $page++)
    @php
        $start = ($page - 1) * $itemsPerPage;
        $end = min($start + $itemsPerPage, $purchaseOrderItemsDetails->count());
        $pageItems = $purchaseOrderItemsDetails->slice($start, $end - $start);
        $totalQuantity = 0;
    @endphp

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="details">
                <h3>Nisu Creations (Pvt) Ltd</h3>
                <p>Tel: 0114063878</p>
                <p>493/5, Makola North, Makola</p>
                <p>Email: nisucreations@gmail.com</p>
            </div>
            <div class="image">
                <img src="{{ public_path('images/logo.jpg') }}" alt="Logo">
            </div>
        </div>

        <!-- Delivery Note Heading -->
        <div class="title">
            Delivery Note
        </div>

        <!-- Delivery Note Details -->
        <div class="invoice-details">
            <table>
                <tr>
                    <th>Delivery Note Number</th>
                    <td>{{ $delivery_note_no }}</td>
                </tr>
                <tr>
                    <th>Deliver Note Date</th>
                    <td>{{ $date }}</td>
                </tr>
                <tr>
                    <th>Your PO Number</th>
                    <td>{{ $invoice->po_number }}</td>
                </tr>
                <tr>
                    <th>Deliver to Customer</th>
                    <td>{{ $customer->name }}</td>
                </tr>
                <tr>
                    <th>Deliver to Address</th>
                    <td>{{ $customer->address }}</td>
                </tr>
            </table>
        </div>

        <!-- Items Table -->
        <div class="items">
            <table class="items-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Item Code</th>
                    <th>Color</th>
                    <th>Color No</th>
                    <th>Size</th>
                    <th>Style</th>
                    <th>UPC</th>
                    <th>More</th>
                    <th>Qty</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($pageItems as $item)
                    @php
                        $totalQuantity += $item->po_qty;
                    @endphp
                    <tr>
                        <td>{{ $start + $loop->iteration }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->color ?? '-' }}</td>
                        <td>{{ $item->color_no ?? '-'  }}</td>
                        <td>{{ $item->size ?? '-' }}</td>
                        <td>{{ $item->style ?? '-' }}</td>
                        <td>{{ $item->upc ?? '-' }}</td>
                        <td>{{ $item->more ?? '-' }}</td>
                        <td>{{ $item->po_qty }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="8" style="text-align: right;">Total Number of Items:</td>
                    <td>{{ $item_count }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="8" style="text-align: right;">Page Total Quantity:</td>
                    <td>{{ $totalQuantity }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="8" style="text-align: right">Total Quantity </td>
                    <td>{{ $purchaseOrderItemsDetails->sum('po_qty') }}</td>></tr>
                </tbody>
            </table>
        </div>

        <!-- Footer Section -->
        <div class="sign-space">
            <p>Customer's Signature: ____________________________________________</p>
            <div class="signatures">
                <div class="signature-box-left">
                    <p>Checked by</p>
                    <br>
                    <p>__________________________</p>
                </div>
                <div class="signature-box-right">
                    <p>Approved by</p>
                    <br>
                    <p>__________________________</p>
                </div>
                <div class="signature-box-center">
                    <p>Received by __________________________</p>
                    <p>Designation __________________________ </p>
                    <p>Date _______________________-</p>
                </div>
            </div>
        </div>
    </div>

    @if (($page) < $totalPages)
        <div class="page-break"></div>
    @endif
@endfor

</body>
</html>
