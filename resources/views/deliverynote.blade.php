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
            font-size: 10px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 5px;
        }
        .header {
            margin-bottom: 90px;
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
            margin-bottom: 5px;
        }
        .invoice-details table {
            width: 100%;
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
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

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
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Color</th>
                <th>Size</th>
                <th>Qty</th>
            </tr>
            </thead>
            <tbody>
            @php
                $totalQuantity = 0;
            @endphp
            @foreach ($purchaseOrderItemsDetails as $item)
                @php
                    $totalQuantity += $item->po_qty;
                @endphp
                <tr>
                    <td>{{ $item->item_code }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->color ?? '-' }}</td>
                    <td>{{ $item->size ?? '-' }}</td>
                    <td>{{ $item->po_qty }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total Quantity:</td>
                <td>{{ $totalQuantity }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
