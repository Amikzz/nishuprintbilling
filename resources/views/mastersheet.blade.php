<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Sheet</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400 ">Invoice & Delivery</a>
            <a href="{{route('mastersheet')}}" class="hover:text-gray-400 text-pink-500">Master Sheet</a>
            <a href="{{ route('return.page') }}" class="hover:text-gray-400 ">Returns</a>
            <a href="{{ route('reports.page') }}" class="hover:text-gray-400">Reports</a>
        </div>
    </div>
</nav>

<div class="container-fluid mx-auto mt-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Master Sheet</h1>
        <!-- Modal Trigger -->
        <button class="px-4 py-2 bg-blue-500 text-white rounded shadow" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
            Add New Invoice
        </button>
    </div>

    <!-- Error and success messages -->
    @if (session('error'))
        <div class="p-4 mb-4 text-white bg-red-500 rounded shadow">
            {{ session('error') }}
        </div>
    @elseif(session('success'))
        <div class="p-4 mb-4 text-white bg-green-500 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Date filter section -->
    <div class="flex gap-4 items-end mb-6">
        <div>
            <label for="start_date" class="block text-gray-700 font-medium mb-1">Start Date:</label>
            <input type="date" id="start_date" class="w-full px-3 py-2 border border-gray-300 rounded shadow">
        </div>
        <div>
            <label for="end_date" class="block text-gray-700 font-medium mb-1">End Date:</label>
            <input type="date" id="end_date" class="w-full px-3 py-2 border border-gray-300 rounded shadow">
        </div>
        <button onclick="filterByDate()" class="px-4 py-2 bg-gray-700 text-white rounded shadow">Filter</button>
    </div>

    <!-- Table for displaying the master sheet -->
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="table-auto w-full text-sm text-left">
            <thead class="bg-gray-800 text-white">
            <tr>
                <th class="px-4 py-2">Our Ref#</th>
                <th class="px-4 py-2">Mail Date</th>
                <th class="px-4 py-2">Required Date</th>
                <th class="px-4 py-2">Create By</th>
                <th class="px-4 py-2">Art Sent Date</th>
                <th class="px-4 py-2">Art Approved Date</th>
                <th class="px-4 py-2">Print Date</th>
                <th class="px-4 py-2">Invoice Date</th>
                <th class="px-4 py-2">Invoice No</th>
                <th class="px-4 py-2">PO Number</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">DN</th>
                <th class="px-4 py-2">DN Date</th>
                <th class="px-4 py-2">PCS</th>
                <th class="px-4 py-2">Invoice Value</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($invoices as $invoice)
                <tr class="@if($invoice->status === 'pending') bg-blue-200
                           @elseif($invoice->status === 'approved') bg-purple-100
                           @elseif($invoice->status === 'printed') bg-purple-300
                           @elseif($invoice->status === 'delivered') bg-green-200
                           @elseif($invoice->status === 'urgent') bg-pink-200
                           @else bg-gray-100 @endif">
                    <td class="border px-4 py-2">{{ $invoice->id ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $invoice->mail_date ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->required_date ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->created_by ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->art_sent_date ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->art_approved_date ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->print_date ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->invoice_date ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->invoice_no ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->cust_ref ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->description ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->dn ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->dn_date ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->pcs ?? '-'}}</td>
                    <td class="border px-4 py-2">{{ $invoice->invoice_value ?? '-'}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Invoice Modal -->
<div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('mastersheet.create') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addInvoiceModalLabel">Add New Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body grid grid-cols-2 gap-4">
                    <div>
                        <label for="mail_date" class="block mb-1">Mail Date</label>
                        <input type="date" id="mail_date" name="mail_date" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="required_date" class="block mb-1">Required Date</label>
                        <input type="date" id="required_date" name="required_date" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="created_by" class="block mb-1">Created By</label>
                        <input type="text" id="created_by" name="created_by" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="print_date" class="block mb-1">Print Date</label>
                        <input type="date" id="print_date" name="print_date" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label for="invoice_date" class="block mb-1">Invoice Date</label>
                        <input type="date" id="invoice_date" name="invoice_date" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label for="cust_ref" class="block mb-1">Purchase Order No</label>
                        <input type="text" id="cust_ref" name="cust_ref" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="description" class="block mb-1">Description</label>
                        <input type="text" id="description" name="description" class="w-full px-3 py-2 border rounded">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Function to filter table rows based on date range
    function filterByDate() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            const mailDate = row.cells[1].textContent.trim();
            const invoiceDate = row.cells[7].textContent.trim();

            const rowStartDate = new Date(mailDate);
            const rowEndDate = new Date(invoiceDate);

            const startFilterDate = startDate ? new Date(startDate) : null;
            const endFilterDate = endDate ? new Date(endDate) : null;

            let showRow = true;

            if (startFilterDate && rowStartDate < startFilterDate) {
                showRow = false;
            }
            if (endFilterDate && rowEndDate > endFilterDate) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
        });
    }
</script>

</body>
</html>
