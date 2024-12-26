<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice & Delivery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #f7fafc;
        }
        .container-fluid {
            padding-left: 30px;
            padding-right: 30px;
        }
        .navbar {
            width: 100%;
        }
        .table {
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
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-gray-400">Purchase Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400 text-pink-500">Invoice & Delivery</a>
            <a href="{{ route('return.page') }}" class="hover:text-gray-400 ">Returns</a>
            <a href="{{ route('reports.page') }}" class="hover:text-gray-400">Reports</a>
        </div>
    </div>
</nav>

<div class="container-fluid mx-auto mt-8">

    <h1 class="text-2xl font-semibold mb-4">Invoice & Delivery Records</h1>

    <!-- Search Form -->
    <form action="{{ route('invoice-databases.index') }}" method="GET" class="mb-4 flex items-center space-x-4">
        <input type="text" name="search" value="{{ request()->search }}" placeholder="Search by Invoice No, Reference No, or PO Number"
               class="p-2 border border-gray-300 rounded-md w-1/2">

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

    <!-- Invoice Table -->
    <div class="overflow-x-auto bg-white p-4 shadow-md rounded-lg">
        <table class="w-full table-auto">
            <thead class="bg-gray-200">
            <tr>
                <th class="px-4 py-2 text-left">Invoice No</th>
                <th class="px-4 py-2 text-left">Delivery Note No</th>
                <th class="px-4 py-2 text-left">Reference No</th>
                <th class="px-4 py-2 text-left">PO Number</th>
                <th class="px-4 py-2 text-left">Date</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-2">{{ $invoice->invoice_no }}</td>
                    <td class="px-4 py-2">{{ $invoice->delivery_note_no ?? 'Delivery Note not created'}}</td>
                    <td class="px-4 py-2">{{ $invoice->reference_no }}</td>
                    <td class="px-4 py-2">{{ $invoice->po_number }}</td>
                    <td class="px-4 py-2">{{ $invoice->date }}</td>
                    <td class="px-4 py-2 {{
                        $invoice->status === 'Order Dispatched' ? 'text-green-600' :
                        ($invoice->status === 'Pending' ? 'text-blue-500' :
                        ($invoice->status === 'Order Complete' ? 'text-orange-500' :
                        ($invoice->status === 'Artwork_approved' ? 'text-gray-500' :
                        ($invoice->status === 'Artwork_sent' ? 'text-yellow-500' : ''))))
                    }}">
                        {{ $invoice->status }}
                    </td>
                    <td class="px-2 py-2 {{ $invoice->status == 'Cancelled' ? 'hidden' : '' }}">
                        <div class="flex flex-col space-y-4">
                            <!-- Download Actions -->
                            <div class="flex space-x-2">
                                <a href="{{ route('invoice.create', ['invoice_number' => $invoice->invoice_no]) }}"
                                   class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-center">
                                    Download Invoice
                                </a>
                                <a href="{{ route('deliverynote.create', ['invoice_number' => $invoice->invoice_no]) }}"
                                   class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-center">
                                    Download Delivery Note
                                </a>
                            </div>

                            <!-- Artwork Actions -->
                            @if($invoice->status == 'Pending')
                                <div class="flex space-x-2 items-center">
                                    <button onclick="openModal('artwork-sent-{{ $invoice->id }}')"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-center">
                                        Artwork Sent
                                    </button>
                                    <form action="{{route('cancel.invoice', $invoice->id)}}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-center">
                                            Cancel Order
                                        </button>
                                    </form>
                                </div>
                                <div id="artwork-sent-{{ $invoice->id }}" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                    <div class="bg-white p-6 rounded shadow-lg w-1/3">
                                        <h2 class="text-xl font-bold mb-4">Artwork Sent</h2>
                                        <form action="{{ route('purchaseorder.artwork', $invoice->id) }}" method="POST">
                                            @csrf
                                            <label for="artwork_sent_by_{{ $invoice->id }}" class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" id="artwork_sent_by_{{ $invoice->id }}" name="artwork_sent_by" placeholder="Enter your name" required
                                                   class="p-2 border border-gray-300 rounded-md w-full mb-4">
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" onclick="closeModal('artwork-sent-{{ $invoice->id }}')"
                                                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                                                    Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            @if($invoice->status == 'Artwork_sent')
                                <div class="flex space-x-2 items-center">
                                    <button onclick="openModal('artwork-approved-{{ $invoice->id }}')"
                                            class="bg-purple-500 text-white px-3 py-1 rounded hover:bg-purple-600 text-center">
                                        Artwork Approved
                                    </button>
                                    <form action="{{route('cancel.invoice', $invoice->id)}}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-center">
                                            Cancel Order
                                        </button>
                                    </form>
                                </div>
                                <div id="artwork-approved-{{ $invoice->id }}" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                    <div class="bg-white p-6 rounded shadow-lg w-1/3">
                                        <h2 class="text-xl font-bold mb-4">Artwork Approved</h2>
                                        <form action="{{ route('purchaseorder.artworkdone', $invoice->id) }}" method="POST">
                                            @csrf
                                            <label for="artwork_approved_by_{{ $invoice->id }}" class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" id="artwork_approved_by_{{ $invoice->id }}" name="artwork_approved_by" placeholder="Enter your name" required
                                                   class="p-2 border border-gray-300 rounded-md w-full mb-4">
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" onclick="closeModal('artwork-approved-{{ $invoice->id }}')"
                                                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                        class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                                                    Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            <div class="flex space-x-4 items-center">

                            <!-- Additional Button -->
                            @if(in_array($invoice->status, ['Pending', 'Artwork_sent', 'Artwork_approved']))
                                    <a href="{{ route('invoices.edit', ['invoice_id' => $invoice->id]) }}"
                                       class="bg-teal-500 text-white px-2 py-1 rounded hover:bg-teal-600 text-center block">
                                        Edit Details
                                    </a>
                                    @endif
                                @if(in_array($invoice->status, ['Pending', 'Artwork_sent']))
                                        <a href="{{route('invoice.details', ['id' => $invoice->id])}}" class="bg-gray-800 text-white px-2 py-1 rounded hover:bg-black text-center block">
                                        Download Details
                                    </a>
                                @endif

                                </div>

                                <!-- Order Actions -->
                            <div class="flex space-x-2 items-center">
                                @if($invoice->status == 'Artwork_approved')
                                    <form action="{{ route('order.dispatch', $invoice->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="bg-pink-500 text-white px-3 py-1 rounded hover:bg-pink-600 text-center">
                                            Order Dispatched
                                        </button>
                                    </form>
                                @elseif($invoice->status == 'Order Dispatched')
                                    <form action="{{ route('order.complete', $invoice->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="bg-orange-600 text-white px-3 py-1 rounded hover:bg-orange-700 text-center">
                                            Order Completed
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- JavaScript for Modals -->
                        <script>
                            function openModal(id) {
                                document.getElementById(id).classList.remove('hidden');
                            }

                            function closeModal(id) {
                                document.getElementById(id).classList.add('hidden');
                            }
                        </script>
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
