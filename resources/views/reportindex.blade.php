@extends('layouts.navbar')

@section('content')
<!-- Flash Messages -->
<div class="container mx-auto mt-8">
    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<!-- Reports Section -->
<div class="max-w-6xl mx-auto mt-12 px-4">
    <!-- Section Header -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Download Reports</h2>
        <p class="text-gray-600 text-sm md:text-base">
            Select the report you want to download. You can filter by date ranges in the next step to get precise data.
        </p>
    </div>

    <!-- Report Buttons Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Sales Report -->
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center hover:shadow-lg transition">
            <h3 class="text-lg font-semibold mb-2 text-gray-800">Sales Report</h3>
            <p class="text-gray-500 text-sm mb-4 text-center">Download a detailed sales report filtered by date ranges.</p>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg w-full transition"
                    data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="Sales Report">
                Download
            </button>
        </div>

        <!-- Purchase Orders Report -->
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center hover:shadow-lg transition">
            <h3 class="text-lg font-semibold mb-2 text-gray-800">Purchase Orders Report</h3>
            <p class="text-gray-500 text-sm mb-4 text-center">Get all purchase orders within a selected date range.</p>
            <button class="bg-teal-500 hover:bg-teal-600 text-white px-5 py-2 rounded-lg w-full transition"
                    data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="Purchase Orders Report">
                Download
            </button>
        </div>

        <!-- Master Sheet Report -->
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center hover:shadow-lg transition">
            <h3 class="text-lg font-semibold mb-2 text-gray-800">Master Sheet Report</h3>
            <p class="text-gray-500 text-sm mb-4 text-center">Download the complete master sheet report for tracking all activities.</p>
            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg w-full transition"
                    data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="Master Sheet Report">
                Download
            </button>
        </div>

        <!-- Pending List -->
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center hover:shadow-lg transition">
            <h3 class="text-lg font-semibold mb-2 text-gray-800">Pending List</h3>
            <p class="text-gray-500 text-sm mb-4 text-center">View all pending orders with relevant details for follow-up.</p>
            <button class="bg-gray-700 hover:bg-gray-800 text-white px-5 py-2 rounded-lg w-full transition"
                    data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="Pending List">
                Download
            </button>
        </div>

        <!-- Completed Orders Report -->
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center hover:shadow-lg transition">
            <h3 class="text-lg font-semibold mb-2 text-gray-800">Completed Orders Report</h3>
            <p class="text-gray-500 text-sm mb-4 text-center">Download all completed orders with date and invoice information.</p>
            <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg w-full transition"
                    data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="Completed Orders Report">
                Download
            </button>
        </div>

        <!-- Summary Report -->
        <div class="bg-white rounded-xl shadow-md p-6 flex flex-col items-center hover:shadow-lg transition">
            <h3 class="text-lg font-semibold mb-2 text-gray-800">Summary Report</h3>
            <p class="text-gray-500 text-sm mb-4 text-center">Download an overall summary of all orders and activities.</p>
            <button class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg w-full transition"
                    data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="All Orders Report">
                Download
            </button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="dateRangeModal" tabindex="-1" aria-labelledby="dateRangeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dateRangeModalLabel">Select Date Range</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="dateRangeForm">
                    <input type="hidden" id="reportType" name="reportType">
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="startDate" name="startDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="endDate" name="endDate" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Capture the report type when the button is clicked
    $('#dateRangeModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var reportType = button.data('report');
        $('#reportType').val(reportType);
        $('#dateRangeModalLabel').text('Select Date Range for ' + reportType);
    });

    // Handle form submission for Purchase Orders Report
    $('#dateRangeForm').on('submit', function (event) {
        event.preventDefault();

        var reportType = $('#reportType').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();

        // Check if the report type is Purchase Orders Report
        if (reportType === 'Purchase Orders Report') {
            var url = "{{ route('report.invoices') }}";
            window.location.href = `${url}?from_date=${startDate}&to_date=${endDate}`;
        } else if (reportType === 'Pending List') {
            var url = "{{ route('report.pendinglist') }}";
            window.location.href = `${url}?from_date=${startDate}&to_date=${endDate}`;
        } else if (reportType === 'Master Sheet Report') {
            var url = "{{ route('report.mastersheet') }}";
            window.location.href = `${url}?from_date=${startDate}&to_date=${endDate}`;
        } else if (reportType === 'Completed Orders Report') {
            var url = "{{ route('report.completeorders') }}";
            window.location.href = `${url}?from_date=${startDate}&to_date=${endDate}`;
        } else if (reportType === 'All Orders Report') {
            var url = "{{ route('report.allorders') }}";
            window.location.href = `${url}?from_date=${startDate}&to_date=${endDate}`;
        } else if (reportType === 'Sales Report') {
            var url = "{{ route('report.sales') }}";
            window.location.href = `${url}?from_date=${startDate}&to_date=${endDate}`;
        }
        else {
            alert('Report type not implemented yet.');
        }

        $('#dateRangeModal').modal('hide');
    });
</script>
@endsection
