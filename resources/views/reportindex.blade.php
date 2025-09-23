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
<div class="container mt-5">
    <h2 class="mb-4">Download Reports</h2>
    <div class="row">
        <div class="col-md-4 mb-3">
            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="Sales Report">Download Sales Report</button>
        </div>
        <div class="col-md-4 mb-3">
            <button class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="Purchase Orders Report">Download Purchase Orders Report</button>
        </div>
        <div class="col-md-4 mb-3">
            <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="Master Sheet Report">Download Master Sheet Report</button>
        </div>
        <div class="col-md-4 mb-3">
            <button class="btn btn-dark w-100" data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="Pending List">Download Pending List</button>
        </div>
        <div class="col-md-4 mb-3">
            <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="Completed Orders Report">Download Completed Orders Report</button>
        </div>
        <div class="col-md-4 mb-3">
            <button class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#dateRangeModal" data-report="All Orders Report">Download Summary Report</button>
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
