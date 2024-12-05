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
            color: #333;
        }
        .container {
            width: 100%;
            margin: 20px auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 200px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-details th, .invoice-details td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .invoice-details th {
            background-color: #f4f4f4;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th, .items-table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .items-table th {
            background-color: #f4f4f4;
        }
        .total {
            text-align: right;
            margin-top: 30px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="{{ public_path('images/logo.jpg') }}" alt="Logo" class="me-2" style="height: 50px;">
        <br>
        <h1>Invoice</h1>
    </div>

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
        </table>
    </div>

    <div class="items">
        <table class="items-table">
            <thead>
            <tr>
                <th>Item Code</th>
                <th>Name</th>
                <th>Color</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($purchaseOrderItemsDetails as $item)
                <tr>
                    <td>{{ $item->item_code }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->color ?? '-' }}</td>
                    <td>{{ $item->size ?? '-'}}</td>
                    <td>{{ $item->po_qty }}</td>
                    <td>{{$item->unit_price}}</td>
                    <td>{{$item->price}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="total">
        <p>Total: ${{ number_format($purchaseOrderItemsDetails->sum('price'), 2) }}</p>
    </div>

{{--    <div class="total">--}}
{{--        <p>Total: ${{ number_format($purchaseOrderItems->sum(function($item) { return $item->price; }), 2) }}</p>--}}
{{--    </div>--}}
</div>

</body>
</html>
