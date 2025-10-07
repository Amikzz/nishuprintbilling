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
            margin-top: 50px;
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

        .totals {
            float: right;
            margin-top: 0px;
            font-size: 10px;
        }
    </style>
</head>
<body>

@php
    $itemsPerPage = 30;
    $totalPages = ceil($purchaseOrderItemsDetails->count() / $itemsPerPage);

    // Calculate the total price and round it to 2 decimal places, then add 0.0030
    $total = $purchaseOrderItemsDetails->sum('price');

    // Calculate the grand total and quantity
    $grandTotal = $total;
    $grandQtyTotal = $purchaseOrderItemsDetails->sum('po_qty');

    // Calculate the converted total using the exchange rate
    $convertedTotal = $grandTotal * $exchangeRate;

    // Format totals to 4 decimal places for display
    $formattedGrandTotal = number_format($grandTotal, 2);
    $formattedConvertedTotal = number_format($convertedTotal, 2);
@endphp

@for ($page = 1; $page <= $totalPages; $page++)
    @php
        $start = ($page - 1) * $itemsPerPage;
        $end = min($start + $itemsPerPage, $purchaseOrderItemsDetails->count());
        $pageItems = $purchaseOrderItemsDetails->slice($start, $end - $start);
        $pageTotal = $pageItems->sum('price');

        $pageQtyTotal = $pageItems->sum('po_qty');
        $pageConvertedTotal = $pageTotal * $exchangeRate;

        // Format page totals to 4 decimal places
        $formattedPageTotal = number_format($pageTotal, 2);
        $formattedPageConvertedTotal = number_format($pageConvertedTotal, 2);
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
                    <th>Delivery Note Number</th>
                    <td>{{$invoice->delivery_note_no}}</td>
                </tr>
                <tr>
                    <th>Invoice Date</th>
                    <td>{{ $date }}</td>
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
                    <td>{{ number_format($exchangeRate) }}</td>
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
                    <th>Item Name</th>
                    <th>Sticker Size</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $start = ($page - 1) * $itemsPerPage;
                @endphp
                @foreach ($pageItems as $item)
                    <tr>
                        <td>{{ $start + $loop->iteration }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->sticker_size ?? '-' }}</td>
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
            @php
                $vatRate = 0.18; // 18%

                // VAT in USD
                $vatAmountUsd = $formattedGrandTotal * $vatRate;

                // USD total with VAT
                $usdWithVat = $formattedGrandTotal + $vatAmountUsd;

                // Converted amounts
                $convertedTotal = $formattedGrandTotal * $exchangeRate;
                $vatAmountLkr = $vatAmountUsd * $exchangeRate;
                $usdWithVat = round($usdWithVat, 2);
                $totalWithVatLkr = $usdWithVat * $exchangeRate;
            @endphp

            <p>
                <b>Page Total Qty:</b> {{ number_format($pageQtyTotal) }} |
                <b>Page Total:</b> ${{ number_format($formattedPageTotal, 2) }}
            </p>

            <p>
                <b>Total Amount:</b> ${{ number_format($formattedGrandTotal, 2) }} |
                <b>VAT (18%):</b> ${{ number_format($vatAmountUsd, 2) }} / Rs. {{ number_format($vatAmountLkr, 2) }}
            </p>

            <p>
                <b>Total with VAT:</b> ${{ number_format($usdWithVat, 2) }} |
                <b>Rs.</b> {{ number_format($totalWithVatLkr, 2) }}
            </p>
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
            </div>
        </div>
    </div>

    @if (($page+1) <= $totalPages)
        <div class="page-break"></div>
    @endif
@endfor

</body>
</html>
