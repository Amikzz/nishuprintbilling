@extends('layouts.navbar')

@section('content')
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
                <option value="null" class="bg-white text-black">No Status</option>
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
            <label for="reference_number" class="block text-gray-700 font-medium mb-1">Reference Number:</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded shadow" id="reference_number" name="reference_number">
                <option value="" disabled selected>Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->item_code }}, {{$item->description}}">
                        {{ $item->item_code }} - {{ $item->description }}
                    </option>
                @endforeach
            </select>
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
                        <label for="invoice_value" class="block mb-1">Invoice Value</label>
                        <input type="text" id="invoice_value" name="invoice_value" class="w-full px-3 py-2 border rounded" required>
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
    function calculateTotals(tabId = null) {
        let totalPCS = 0;
        let totalInvoiceValue = 0;
        let visibleRows = 0;

        // If no tabId is passed, get the active tab
        if (!tabId) {
            const activeTabLink = document.querySelector('#invoiceTabs .nav-link.active');
            tabId = activeTabLink ? activeTabLink.getAttribute('href').substring(1) : null;
        }

        if (!tabId) return;

        const rows = document.querySelectorAll(`#${tabId} tbody tr`);

        rows.forEach(row => {
            if (row.style.display === 'none') return; // Skip hidden rows

            const pcs = parseFloat(row.cells[13].textContent.trim()) || 0;
            const invoiceValue = parseFloat(row.cells[14].textContent.trim()) || 0;

            totalPCS += pcs;
            totalInvoiceValue += invoiceValue;
            visibleRows++;
        });

        document.getElementById("visible_rows_count").textContent = visibleRows;
        document.getElementById("total_pcs").textContent = totalPCS;
        document.getElementById("total_invoice_value").textContent = totalInvoiceValue.toFixed(2);
    }

    $(document).ready(function () {
        $('#invoiceTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
            const tabId = $(this).attr('href').substring(1);
            calculateTotals(tabId);
            localStorage.setItem('activeTab', tabId);
        });

        const activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#invoiceTabs a[href="#' + activeTab + '"]').tab('show');
            calculateTotals(activeTab);
        } else {
            const firstTab = $('#invoiceTabs a:first').attr('href').substring(1);
            calculateTotals(firstTab);
        }
    });
    function filterByCriteria() {
        const dateFilter = document.getElementById('date_filter').value;
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const poNumber = document.getElementById('po_number').value.trim().toLowerCase();
        const invoiceNumber = document.getElementById('invoice_number').value.trim().toLowerCase();
        const dn = document.getElementById('dn').value.trim().toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.trim().toLowerCase();
        const selectedReference = document.getElementById('reference_number').value.trim().toLowerCase();

        const activeTabLink = document.querySelector('#invoiceTabs .nav-link.active');
        const activeTabId = activeTabLink ? activeTabLink.getAttribute('href').substring(1) : null;
        if (!activeTabId) return;

        const rows = document.querySelectorAll(`#${activeTabId} tbody tr`);
        rows.forEach(row => {
            const mailDate = row.cells[1].textContent.trim();
            const requiredDate = row.cells[2].textContent.trim();
            const invoiceDate = row.cells[7].textContent.trim();
            const rowPONumber = row.cells[9].textContent.trim().toLowerCase();
            const rowInvoiceNumber = row.cells[8].textContent.trim().toLowerCase();
            const rowDN = row.cells[11].textContent.trim().toLowerCase();
            const rowStatus = row.cells[15].textContent.trim().toLowerCase();
            const rowDescription = row.cells[10].textContent.trim().toLowerCase(); // Reference column

            const rowMailDate = parseDate(mailDate);
            const rowRequiredDate = parseDate(requiredDate);
            const rowInvoiceDate = parseDate(invoiceDate);

            const startFilterDate = startDate ? parseDate(startDate) : null;
            const endFilterDate = endDate ? parseDate(endDate) : null;

            let showRow = true;

            if (poNumber && !rowPONumber.includes(poNumber)) showRow = false;
            if (invoiceNumber && !rowInvoiceNumber.includes(invoiceNumber)) showRow = false;
            if (dn && !rowDN.includes(dn)) showRow = false;
            if (selectedReference && !rowDescription.includes(selectedReference)) showRow = false;

            if (statusFilter) {
                if (statusFilter === "null") {
                    showRow = !rowStatus || rowStatus === "-" || rowStatus === "null";
                } else {
                    showRow = rowStatus === statusFilter;
                }
            }

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

            row.style.display = showRow ? '' : 'none';
        });

        // Update totals after filtering
        calculateTotals(activeTabId);
    }


    function parseDate(dateStr) {
        const parsedDate = new Date(dateStr);
        return isNaN(parsedDate.getTime()) ? null : parsedDate;
    }
</script>
@endsection
