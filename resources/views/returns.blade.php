<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #f7fafc;
        }
        .container-fluid {
            padding-left: 190px;
            padding-right: 190px;
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
<nav class="bg-black p-3">
    <div class="flex items-center justify-between container-fluid">
        <a href="{{ route('home') }}" class="flex items-center text-white">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="mr-3" style="height: 80px;">
        </a>
        <div class="flex space-x-6 text-white">
            <a href="{{ route('home') }}" class="hover:text-gray-400">Home</a>
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-gray-400">All Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400">Invoice & Delivery</a>
            <a href="{{route('mastersheet')}}" class="hover:text-gray-400">Master Sheet</a>
            <a href="{{route('urgentorders')}}" class="hover:text-gray-400">Urgent Orders</a>
            <a href="{{ route('return.page') }}" class="hover:text-gray-400 text-pink-500">Returns</a>
            <a href="{{ route('reports.page') }}" class="hover:text-gray-400">Reports</a>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<div class="container-fluid mt-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search Form -->
    <form id="searchForm" action="{{ route('search.returninvoice') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="delivery_note_no">Delivery Note Number</label>
            <input type="text" id="delivery_note_no" name="delivery_note_no" class="form-control" value="{{ old('delivery_note_no') }}" required>
        </div>

        <!-- Buttons aligned side by side -->
        <div class="d-flex justify-content-start gap-2">
            <button type="submit" class="btn btn-primary">Update Return Invoice Number</button>
        </div>
    </form>

    <!-- View Updated Records Form -->
    <form id="viewRecordsForm" action="{{ route('view.updated.records') }}" method="GET">
        @csrf
        <input type="hidden" id="view_delivery_note_number" name="delivery_note_no" value="">
        <button type="submit" class="btn btn-secondary mt-2">View Updated Records</button>
    </form>

<script>
    $(document).ready(function () {
        // Handle View Updated Records Form Submission
        $('#viewRecordsForm').on('submit', function (event) {
            const deliveryNoteno = $('#delivery_note_no').val();
            if (!deliveryNoteno) {
                event.preventDefault();
                alert('Please enter delivery note number.');
                return;
            }
            $('#view_delivery_note_number').val(deliveryNoteno);
        });
    });
</script>
</div>
</body>
</html>
