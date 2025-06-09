<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order Summary Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        h2, h4 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            margin-bottom: 30px;
            table-layout: fixed; /* Important for fixed column widths */
        }

        th, td {
            border: 1px solid #444;
            padding: 10px;
            text-align: right;
            word-wrap: break-word;
        }

        th {
            background-color: #eee;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .borderless td {
            border: none;
        }

        .total {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .section-title {
            margin-top: 50px;
            font-size: 1.2em;
            text-decoration: underline;
        }

        /* Fixed column widths */
        .col-desc {
            width: 55%;
        }

        .col-usd {
            width: 22.5%;
        }

        .col-lkr {
            width: 22.5%;
        }
    </style>
</head>
<body>

<h2>NISU CREATIONS</h2>
<h4>Purchase Order Summary as at {{ \Carbon\Carbon::parse($to_date)->format('d F Y') }}</h4>
<h4>Exchange Rate Used: {{ number_format($rate, 2) }} LKR/USD</h4>

<!-- PO Value Section -->
<table>
    <thead>
    <tr>
        <th class="text-left col-desc">Income</th>
        <th class="col-usd">USD ($)</th>
        <th class="col-lkr">LKR (Rs.)</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="text-left">Current Month PO Value</td>
        <td>{{ number_format($summary['invoiced_in_range'] + $summary['not_invoiced_in_range'], 2) }}</td>
        <td>{{ number_format(($summary['invoiced_in_range'] + $summary['not_invoiced_in_range']) * $rate, 2) }}</td>
    </tr>
    <tr>
        <td class="text-left">Previous Month(s) Carried Forward</td>
        <td>{{ number_format($summary['carried_forward'], 2) }}</td>
        <td>{{ number_format($summary['carried_forward'] * $rate, 2) }}</td>
    </tr>
    <tr class="total">
        <td class="text-left">Total Value</td>
        <td>{{ number_format(($summary['invoiced_in_range'] + $summary['not_invoiced_in_range']) + $summary['carried_forward'], 2) }}</td>
        <td>{{ number_format((($summary['invoiced_in_range'] + $summary['not_invoiced_in_range']) + $summary['carried_forward']) * $rate, 2) }}</td>
    </tr>
    </tbody>
</table>

<!-- Invoice Summary Section -->
<table>
    <thead>
    <tr>
        <th class="text-left col-desc">Invoice Summary</th>
        <th class="col-usd">USD ($)</th>
        <th class="col-lkr">LKR (Rs.)</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="text-left">Invoiced This Month (Current)</td>
        <td>{{ number_format($summary['invoiced_in_range'], 2) }}</td>
        <td>{{ number_format($summary['invoiced_in_range'] * $rate, 2) }}</td>
    </tr>
    <tr>
        <td class="text-left">Invoiced This Month (Previous)</td>
        <td>{{ number_format($summary['invoiced_from_previous'], 2) }}</td>
        <td>{{ number_format($summary['invoiced_from_previous'] * $rate, 2) }}</td>
    </tr>
    <tr class="total">
        <td class="text-left">Total Invoiced</td>
        <td>{{ number_format($summary['invoiced_in_range'] + $summary['invoiced_from_previous'], 2) }}</td>
        <td>{{ number_format(($summary['invoiced_in_range'] + $summary['invoiced_from_previous']) * $rate, 2) }}</td>
    </tr>
    </tbody>
</table>

<!-- Balance Section -->
<table>
    <thead>
    <tr>
        <th class="text-left col-desc">Balance Carried Forward</th>
        <th class="col-usd">USD ($)</th>
        <th class="col-lkr">LKR (Rs.)</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="text-left">Remaining From Previous Months</td>
        <td>{{ number_format($summary['remaining_from_previous'] - $summary['invoiced_from_previous'], 2) }}</td>
        <td>{{ number_format(($summary['remaining_from_previous'] - $summary['invoiced_from_previous']) * $rate, 2) }}</td>
    </tr>
    <tr>
        <td class="text-left">Current Month Not Invoiced</td>
        <td>{{ number_format($summary['not_invoiced_in_range'], 2) }}</td>
        <td>{{ number_format($summary['not_invoiced_in_range'] * $rate, 2) }}</td>
    </tr>
    <tr class="total">
        <td class="text-left">Total Balance to Invoice</td>
        <td>{{ number_format(($summary['remaining_from_previous'] - $summary['invoiced_from_previous']) + $summary['not_invoiced_in_range'], 2) }}</td>
        <td>{{ number_format((($summary['remaining_from_previous'] - $summary['invoiced_from_previous']) + $summary['not_invoiced_in_range']) * $rate, 2) }}</td>
    </tr>
    </tbody>
</table>

</body>
</html>
