<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders</title>
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
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-gray-400 text-pink-500">Purchase Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400">Invoice & Delivery</a>
        </div>
    </div>
</nav>

<div class="container mx-auto mt-8">

    <h1 class="text-2xl font-semibold mb-4">Purchase Orders</h1>

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
                <th class="px-4 py-2 text-left">Color Name</th>
                <th class="px-4 py-2 text-left">Color No</th>
                <th class="px-4 py-2 text-left">Size</th>
                <th class="px-4 py-2 text-left">Style</th>
                <th class="px-4 py-2 text-left">UPC No</th>
                <th class="px-4 py-2 text-left">Quantity</th>
                <th class="px-4 py-2 text-left">Price</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Actions</th>
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
                    <td class="px-4 py-2">{{ $order->color_name ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->color_no ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->size ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->style ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->upc_no ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $order->po_qty }}</td>
                    <td class="px-4 py-2">{{ $order->price }}</td>
                    <td class="px-4 py-2
                        {{ $order->status === 'Artwork_needed' ? 'text-red-500' : '' }}
                        {{ $order->status === 'Artwork_sent' ? 'text-yellow-500' : '' }}
                        {{ $order->status === 'Artwork_approved' ? 'text-green-500' : '' }}">
                        {{ $order->status }}
                    </td>

                    <td class="px-4 py-2">
                        <div class="flex space-x-2">

                            @if($order->status === 'Pending')
                                <form action="{{ route('purchaseorder.artwork', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        <center>Artwork Need</center>
                                    </button>
                                </form>
                            @endif

                            @if($order->status === 'Artwork_needed')
                                <form action="{{ route('purchaseorder.artwork', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        <center>Artwork Sent</center>
                                    </button>
                                </form>
                            @endif

                            @if($order->status === 'Artwork_sent')
                                <form action="{{ route('purchaseorder.artworkdone', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                        <center>Artwork Approved</center>
                                    </button>
                                </form>
                            @endif

                            <!-- Delete Button -->
                            <form action="{{ route('purchase-order-databases.destroy', $order->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Are you sure you want to delete this record?');">
                                    <center>Delete Order</center>
                                </button>
                            </form>
                        </div>
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
