<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice & Delivery</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-gray-800 p-4">
    <div class="flex items-center justify-between container mx-auto">
        <a href="{{ route('home') }}" class="flex items-center text-white">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="mr-3" style="height: 80px;">
        </a>
        <div class="flex space-x-6 text-white">
            <a href="{{ route('home') }}" class="hover:text-gray-400">Home</a>
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-gray-400">Purchase Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400 text-pink-500">Invoice & Delivery</a>
        </div>
    </div>
</nav>

<div class="container mx-auto mt-8">

    <h1 class="text-2xl font-semibold mb-4">Invoice & Delivery Records</h1>

    <!-- Search Form -->
    <form action="{{ route('invoice-databases.index') }}" method="GET" class="mb-4 flex items-center space-x-4">
        <input type="text" name="search" value="{{ request()->search }}" placeholder="Search by Invoice No, Reference No, or PO Number"
               class="p-2 border border-gray-300 rounded-md w-1/2">
        <button type="submit" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Search</button>
    </form>

    <!-- Display Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded-md mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Invoice Table -->
    <div class="overflow-x-auto bg-white p-4 shadow-md rounded-lg">
        <table class="w-full table-auto">
            <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 text-left">Invoice No</th>
                <th class="px-4 py-2 text-left">Reference No</th>
                <th class="px-4 py-2 text-left">PO Number</th>
                <th class="px-4 py-2 text-left">Date</th>
                <th class="px-4 py-2 text-left">Actions</th> <!-- New column for actions -->
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-2">{{ $invoice->invoice_no }}</td>
                    <td class="px-4 py-2">{{ $invoice->reference_no }}</td>
                    <td class="px-4 py-2">{{ $invoice->po_number }}</td>
                    <td class="px-4 py-2">{{ $invoice->date }}</td>
                    <td class="px-4 py-2">
                        <div class="flex space-x-2">
                            <!-- Download Invoice Button -->
                            <a href="{{ route('invoice.create', ['invoice_number' => $invoice->invoice_no]) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Download Invoice</a>
                            <!-- Download Delivery Note Button -->
                            <a href="{{route('deliverynote.create', ['invoice_number' => $invoice->invoice_no])}}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Download Delivery Note</a>
                            <!-- Order Dispatched Button -->
                            <a href="#" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Order Dispatched</a>
                            <!-- Order Completed Button -->
                            <a href="#" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Order Completed</a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $invoices->links('pagination::tailwind') }}
    </div>
</div>

</body>

</html>
