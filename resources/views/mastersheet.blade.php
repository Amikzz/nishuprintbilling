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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            <a href="{{route('urgentorders')}}" class="hover:text-gray-400">Urgent Orders</a>
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
            Add New Purchase Order
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

    <div class="flex gap-4 items-end mb-6">
        <div>
            <label for="statusFilter" class="block text-gray-700 font-medium mb-1">Filter By Color:</label>
            <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded shadow">
                <option value="" class="bg-white">All</option>
                <option value="pending" class="bg-blue-600 text-white">Pending</option>
                <option value="approved" class="bg-purple-300 text-black">Approved</option>
                <option value="printed" class="bg-purple-600 text-white">Printed</option>
                <option value="delivered" class="bg-green-600 text-white">Delivered</option>
                <option value="urgent" class="bg-pink-600 text-white">Urgent</option>
            </select>
        </div>

        <div>
            <label for="date_filter" class="block text-gray-700 font-medium mb-1">Filter By Date:</label>
            <select id="date_filter" class="w-full px-3 py-2 border border-gray-300 rounded shadow">
                <option value="mail_date">Mail Date</option>
                <option value="required_date">Required Date</option>
                <option value="invoice_date">Invoice Date</option>
            </select>
        </div>

        <div>
            <label for="start_date" class="block text-gray-700 font-medium mb-1">Start Date:</label>
            <input type="date" id="start_date" class="w-full px-3 py-2 border border-gray-300 rounded shadow">
        </div>
        <div>
            <label for="end_date" class="block text-gray-700 font-medium mb-1">End Date:</label>
            <input type="date" id="end_date" class="w-full px-3 py-2 border border-gray-300 rounded shadow">
        </div>
        <div>
            <label for="po_number" class="block text-gray-700 font-medium mb-1">PO Number:</label>
            <input type="text" id="po_number" class="w-full px-3 py-2 border border-gray-300 rounded shadow" placeholder="Enter PO Number">
        </div>
        <div>
            <label for="invoice_number" class="block text-gray-700 font-medium mb-1">Invoice Number:</label>
            <input type="text" id="invoice_number" class="w-full px-3 py-2 border border-gray-300 rounded shadow" placeholder="Enter Invoice Number">
        </div>
        <div>
            <label for="dn" class="block text-gray-700 font-medium mb-1">DN:</label>
            <input type="text" id="dn" class="w-full px-3 py-2 border border-gray-300 rounded shadow" placeholder="Enter DN">
        </div>
        <button onclick="filterByCriteria()" class="px-4 py-2 bg-gray-700 text-white rounded shadow">Filter</button>
    </div>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" id="invoiceTabs" role="tablist">
        @foreach($invoices as $month => $records)
            @php
                $tabId = strtolower(str_replace(' ', '-', $month)); // Convert to safe format
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                   data-toggle="tab"
                   href="#{{ $tabId }}"
                   role="tab">
                    {{ $month }}
                </a>
            </li>
        @endforeach
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-4">
        @foreach ($invoices as $month => $monthInvoices)
            @php
                $tabId = strtolower(str_replace(' ', '-', $month)); // Ensure consistent tab ID
            @endphp
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                 id="{{ $tabId }}"
                 role="tabpanel">
                <div class="bg-gray-700 text-white text-lg font-bold p-2">{{ $month }}</div>
                <table class="table-auto w-full text-sm text-left mb-4">
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
                        <th class="px-4 py-2">Total PCS</th>
                        <th class="px-4 py-2">Invoice Value</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($monthInvoices as $invoice)
                        <tr class="
                            @if($invoice->status === 'pending') bg-blue-600 font-bold
                            @elseif($invoice->status === 'approved') bg-purple-300 font-bold
                            @elseif($invoice->status === 'printed') bg-purple-600 font-bold
                            @elseif($invoice->status === 'delivered') bg-green-600 font-bold
                            @elseif($invoice->status === 'urgent') bg-pink-600 font-bold
                            @elseif($invoice->status === null) bg-gray-200 font-bold
                            @else bg-gray-100
                            @endif">
                            <td class="border px-4 py-2">{{ $invoice->id ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->mail_date ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->required_date ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->created_by ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->art_sent_date ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->art_approved_date ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->print_date ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->invoice_date ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->invoice_no ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->cust_ref ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->description ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->dn ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->dn_date ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->pcs ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->invoice_value ?? '-' }}</td>
                            <td class="border px-4 py-2">{{ $invoice->status ?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <!-- jQuery and Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function(){
            // Ensure Bootstrap tabs work properly
            $('#invoiceTabs a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Restore active tab on page reload
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#invoiceTabs a[href="' + activeTab + '"]').tab('show');
            }

            $('#invoiceTabs a').on('shown.bs.tab', function (e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
        });
    </script>

    <!-- Totals Section -->
    <div class="mt-4 bg-gray-100 p-4 rounded shadow">
        <h2 class="text-lg font-semibold">Summary</h2>
        <p><strong>Number of Rows:</strong> <span id="visible_rows_count">0</span></p>
        <p><strong>Total PCS:</strong> <span id="total_pcs">0</span></p>
        <p><strong>Total Invoice Value:</strong> <span id="total_invoice_value">0.00</span></p>
    </div>
</div>

<!-- Add Invoice Modal -->
<div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('mastersheet.create') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addInvoiceModalLabel">Add New Purchase Order</h5>
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
                        <label for="cust_ref" class="block mb-1">Purchase Order No</label>
                        <input type="text" id="cust_ref" name="cust_ref" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div>
                        <label for="description" class="block mb-1">Item</label>
                        <select class="form-select item-select" id="description" name="description">
                            <option value="" disabled selected>Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->item_code }}, {{$item->description}}">
                                    {{ $item->item_code }} - {{ $item->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Purchase Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        function calculateTotals(tabId) {
            let totalPCS = 0;
            let totalInvoiceValue = 0;
            let visibleRows = 0;

            $("#" + tabId + " tbody tr").each(function () {
                let pcs = parseFloat($(this).find("td:eq(13)").text()) || 0;
                let invoiceValue = parseFloat($(this).find("td:eq(14)").text()) || 0;
                totalPCS += pcs;
                totalInvoiceValue += invoiceValue;
                visibleRows++;
            });

            $("#visible_rows_count").text(visibleRows);
            $("#total_pcs").text(totalPCS);
            $("#total_invoice_value").text(totalInvoiceValue.toFixed(2));
        }

        // Ensure Bootstrap tabs work properly
        $('#invoiceTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
            let tabId = $(this).attr('href').substring(1);
            calculateTotals(tabId);
            localStorage.setItem('activeTab', tabId);
        });

        // Restore active tab and calculate totals on page load
        let activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#invoiceTabs a[href="#' + activeTab + '"]').tab('show');
            calculateTotals(activeTab);
        } else {
            let firstTab = $('#invoiceTabs a:first').attr('href').substring(1);
            calculateTotals(firstTab);
        }
    });

    function filterByCriteria() {
        const dateFilter = document.getElementById('date_filter').value; // Get selected date field
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const poNumber = document.getElementById('po_number').value.trim().toLowerCase();
        const invoiceNumber = document.getElementById('invoice_number').value.trim().toLowerCase();
        const dn = document.getElementById('dn').value.trim().toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.trim().toLowerCase(); // Get selected status

        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            const mailDate = row.cells[1].textContent.trim(); // Mail Date
            const requiredDate = row.cells[2].textContent.trim(); // Required Date
            const invoiceDate = row.cells[7].textContent.trim(); // Invoice Date
            const rowPONumber = row.cells[9].textContent.trim().toLowerCase(); // PO Number
            const rowInvoiceNumber = row.cells[8].textContent.trim().toLowerCase(); // Invoice Number
            const rowDN = row.cells[11].textContent.trim().toLowerCase(); // DN
            const rowStatus = row.cells[15].textContent.trim().toLowerCase(); // Status (assuming it's in the 13th column)

            // Convert dates to Date objects
            const rowMailDate = parseDate(mailDate);
            const rowRequiredDate = parseDate(requiredDate);
            const rowInvoiceDate = parseDate(invoiceDate);

            const startFilterDate = startDate ? parseDate(startDate) : null;
            const endFilterDate = endDate ? parseDate(endDate) : null;

            let showRow = true;

            // Check PO Number
            if (poNumber && !rowPONumber.includes(poNumber)) {
                showRow = false;
            }

            // Check Invoice Number
            if (invoiceNumber && !rowInvoiceNumber.includes(invoiceNumber)) {
                showRow = false;
            }

            // Check DN
            if (dn && !rowDN.includes(dn)) {
                showRow = false;
            }

            // Check Status Filter
            if (statusFilter && rowStatus !== statusFilter) {
                showRow = false;
            }

            // Apply date filters depending on the selected column
            if (dateFilter === 'mail_date') {
                if (startFilterDate && rowMailDate < startFilterDate) showRow = false;
                if (endFilterDate && rowMailDate > endFilterDate) showRow = false;
            } else if (dateFilter === 'required_date') {
                if (startFilterDate && rowRequiredDate < startFilterDate) showRow = false;
                if (endFilterDate && rowRequiredDate > endFilterDate) showRow = false;
            } else if (dateFilter === 'invoice_date') {
                if (startFilterDate && rowInvoiceDate < startFilterDate) showRow = false;
                if (endFilterDate && rowInvoiceDate > endFilterDate) showRow = false;
            }

            // Show or hide the row based on the result
            row.style.display = showRow ? '' : 'none';
        });

        // Call the calculateTotals function to update totals after filtering
        calculateTotals();
    }

    // Function to parse different date formats into a Date object
    function parseDate(dateStr) {
        // Try to parse the date, and return a Date object or null if invalid
        const parsedDate = new Date(dateStr);
        if (parsedDate.toString() === 'Invalid Date') {
            return null;  // If parsing fails, return null
        }
        return parsedDate;
    }

    // Calculate totals initially when the page loads
    calculateTotals();

</script>
</body>
</html>
