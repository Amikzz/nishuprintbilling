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
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-gray-400">All Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400 text-pink-500">Invoice & Delivery</a>
            <a href="{{route('mastersheet')}}" class="hover:text-gray-400">Master Sheet</a>
            <a href="{{route('urgentorders')}}" class="hover:text-gray-400">Urgent Orders</a>
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
                    <td class="px-4 py-2">{{ $invoice->invoice_no ?? 'Invoice Not Created'}}</td>
                    <td class="px-4 py-2">{{ $invoice->delivery_note_no ?? 'Delivery Note not created'}}</td>
                    <td class="px-4 py-2">{{ $invoice->reference_no }}</td>
                    <td class="px-4 py-2">{{ $invoice->po_number }}</td>
                    <td class="px-4 py-2">{{ $invoice->date }}</td>
                    <td class="px-4 py-2 {{
                        $invoice->status === 'Order Dispatched' ? 'text-green-600' :
                        ($invoice->status === 'Pending' ? 'text-blue-500' :
                        ($invoice->status === 'Order Complete' ? 'text-green-600' :
                        ($invoice->status === 'Artwork_approved' ? 'text-orange-500' :
                        ($invoice->status === 'Items_printed' ? 'text-purple-500' :
                        ($invoice->status === 'Urgent' ? 'text-pink-600' :
                        ($invoice->status === 'Artwork_sent' ? 'text-yellow-500' : ''))))))
                    }}">
                        {{ $invoice->status }}
                    </td>
                    <td class="px-2 py-2 {{ $invoice->status == 'Cancelled' ? 'hidden' : '' }}">
                        <div class="flex flex-col space-y-4">
                            <!-- Download Actions -->
                            <div class="flex space-x-2">
                                <button onclick="openModal('invoice-{{ $invoice->po_number }}')" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                    Download Invoice
                                </button>
                                <button onclick="openModal('deliverynote-{{ $invoice->po_number }}')" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                    Download Delivery Note
                                </button>
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

                            @if($invoice->status == 'Artwork_approved')

                                <div class="flex space-x-2">
                                    <a href="{{ route('purchaseorder.printed', ['invoice_id' => $invoice->id]) }}"
                                       class="bg-purple-400 text-white px-3 py-1 rounded hover:bg-purple-600 text-center">
                                        &nbsp; &nbsp; &nbsp; Printed &nbsp; &nbsp; &nbsp;
                                    </a>
                                    <a href="{{ route('purchaseorder.urgent', ['invoice_id' => $invoice->id]) }}"
                                       class="bg-pink-600 px-3 py-1 rounded hover:bg-pink-700 text-white text-center">
                                        &nbsp; &nbsp; &nbsp; Urgent &nbsp; &nbsp; &nbsp;
                                    </a>
                                </div>
                            @endif


                            <div class="flex space-x-4 items-center">

                                <!-- Additional Button -->
                                @if(in_array($invoice->status, ['Pending', 'Artwork_sent', 'Artwork_approved', 'Items_printed', 'Order Dispatched']))
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

                            <!-- Invoice Modal -->
                            <div id="invoice-{{ $invoice->po_number }}" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                <div class="bg-white p-6 rounded shadow-lg w-1/3">
                                    <h2 class="text-xl font-bold mb-4">Add Invoice Number</h2>
                                    <form action="{{ route('invoice.create', $invoice->po_number) }}" method="GET">
                                        @csrf
                                        <label for="invoice_number_{{ $invoice->po_number }}" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                                        <input type="text" id="invoice_number_{{ $invoice->po_number }}" name="invoice_number" placeholder="Enter Invoice Number" required
                                               class="p-2 border border-gray-300 rounded-md w-full mb-4">
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" onclick="closeModal('invoice-{{ $invoice->po_number }}')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                                Cancel
                                            </button>
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                                Submit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Delivery Note Modal -->
                            <div id="deliverynote-{{ $invoice->po_number }}" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                <div class="bg-white p-6 rounded shadow-lg w-1/3">
                                    <h2 class="text-xl font-bold mb-4">Add Delivery Note Number</h2>
                                    <form action="{{ route('deliverynote.create', $invoice->po_number) }}" method="GET">
                                        @csrf
                                        <label for="delivery_note_number_{{ $invoice->po_number }}" class="block text-sm font-medium text-gray-700">Delivery Note Number</label>
                                        <input type="text" id="delivery_note_number_{{ $invoice->po_number }}" name="delivery_note_number" placeholder="Enter Delivery Note Number" required
                                               class="p-2 border border-gray-300 rounded-md w-full mb-4">
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" onclick="closeModal('deliverynote-{{ $invoice->po_number }}')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                                Cancel
                                            </button>
                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                                Submit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Order Actions -->
                            <div class="flex space-x-2 items-center">
                                @if($invoice->status == 'Items_printed')
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

                            document.addEventListener('DOMContentLoaded', () => {
                                const forms = document.querySelectorAll('form'); // Select all forms within modals

                                forms.forEach(form => {
                                    form.addEventListener('submit', (event) => {
                                        const modal = form.closest('.fixed'); // Find the closest modal container
                                        if (modal) {
                                            modal.classList.add('hidden'); // Hide the modal
                                        }

                                        // Optional: Refresh the page after submission
                                        setTimeout(() => {
                                            window.location.reload();
                                        }, 500); // Add a delay to ensure backend processing
                                    });
                                });
                            });

                        </script>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
</div>
</body>

</html>
