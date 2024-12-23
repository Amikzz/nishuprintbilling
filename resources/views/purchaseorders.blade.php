<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #f7fafc; /* Tailwind's bg-gray-100 equivalent */
        }
        .container-fluid {
            padding-left: 30px;
            padding-right: 30px;
        }
        .navbar {
            width: 100%;
        }
    </style>

</head>
<body>

<!-- Navbar -->
<nav class="bg-gray-800 p-3">
    <div class="flex items-center justify-between container-fluid">
        <a href="{{ route('home') }}" class="flex items-center text-white">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="mr-3" style="height: 80px;">
        </a>
        <div class="flex space-x-6 text-white">
            <a href="{{ route('home') }}" class="hover:text-gray-400">Home</a>
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-gray-400 text-pink-500">All Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400">Invoice & Delivery</a>
            <a href="{{ route('return.page') }}" class="hover:text-gray-400 ">Returns</a>
            <a href="{{ route('reports.page') }}" class="hover:text-gray-400">Reports</a>
        </div>
    </div>
</nav>

<div class="container-fluid mx-auto mt-8">

    <h1 class="text-2xl font-semibold mb-4">All Orders</h1>

    <!-- Search Form -->
    <form action="{{ route('purchase-order-databases.index') }}" method="GET" class="mb-4 flex items-center space-x-4">
        <input type="text" name="search" value="{{ request()->search }}" placeholder="Search by Reference No, PO No, or Item Code"
               class="p-2 border border-gray-300 rounded-md w-1/2">

        <!-- Date Range Filters -->
        <input type="date" name="start_date" value="{{ request()->start_date }}" class="p-2 border border-gray-300 rounded-md">
        <input type="date" name="end_date" value="{{ request()->end_date }}" class="p-2 border border-gray-300 rounded-md">

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

    <form action="{{ route('purchase-order-databases.export') }}" method="GET" class="inline">
        <button type="submit" class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">
            Download Excel
        </button>
    </form>

    <br>
    <br>

    <!-- Purchase Orders Table -->
    <div class="overflow-x-auto bg-white p-4 shadow-md rounded-lg">
        <table class="w-full table-auto">
            <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Date</th>
                <th class="px-4 py-2 text-left">Reference No</th>
                <th class="px-4 py-2 text-left">PO No</th>
                <th class="px-4 py-2 text-left">Item Code</th>
                <th class="px-4 py-2 text-left">Color No</th>
                <th class="px-4 py-2 text-left">Color Name</th>
                <th class="px-4 py-2 text-left">Size</th>
                <th class="px-4 py-2 text-left">Sticker Size</th>
                <th class="px-4 py-2 text-left">Style</th>
                <th class="px-4 py-2 text-left">UPC No</th>
                <th class="px-4 py-2 text-left">Quantity</th>
                <th class="px-4 py-2 text-left">Price</th>
                <th class="px-4 py-2 text-left">More 1</th>
                <th class="px-4 py-2 text-left">More 2</th>
                <th class="px-4 py-2 text-left">Status</th>
            </tr>
            </thead>
            <tbody>
            @forelse($purchaseOrders as $order)
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-2">{{ $loop->iteration + ($purchaseOrders->currentPage() - 1) * $purchaseOrders->perPage() }}</td>
                    <td class="px-4 py-2">{{ $order->date }}</td>
                    <td class="px-4 py-2">{{ $order->reference_no }}</td>
                    <td class="px-4 py-2">{{ $order->po_no }}</td>
                    <td class="px-4 py-2">{{ $order->item_code }}</td>
                    <td class="px-4 py-2">{{ $order->color_no ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->color_name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->size ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->items->first()->description ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->style ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->upc_no ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->po_qty }}</td>
                    <td class="px-4 py-2">{{ $order->price }}</td>
                    <td class="px-4 py-2">{{ $order->more1 }}</td>
                    <td class="px-4 py-2">{{ $order->more2 }}</td>
                    <td class="px-4 py-2
                        {{ $order->status === 'Pending' ? 'text-blue-500' : '' }}
                        {{ $order->status === 'Artwork_sent' ? 'text-yellow-500' : '' }}
                        {{ $order->status === 'Artwork_approved' ? 'text-gray-500' : '' }}
                        {{ $order->status === 'Order Dispatched' ? 'text-green-500' : '' }}
                        {{ $order->status === 'Order Complete' ? 'text-orange-500' : '' }}
                        {{ $order->status === 'Cancelled' ? 'text-red-500' : '' }}
                        ">
                        {{ $order->status }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center p-4">No purchase orders found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $purchaseOrders->links('pagination::tailwind') }}
    </div>
</div>

</body>
</html>
