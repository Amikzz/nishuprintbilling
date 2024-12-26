<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Page</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-black p-3">
    <div class="flex items-center justify-between container mx-auto">
        <a href="{{ route('home') }}" class="flex items-center text-white">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="mr-3" style="height: 80px;">
        </a>
        <div class="flex space-x-6 text-white">
            <a href="{{ route('home') }}" class="hover:text-gray-400">Home</a>
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-gray-400">All Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400">Invoice & Delivery</a>
            <a href="{{ route('return.page') }}" class="hover:text-gray-400 ">Returns</a>
            <a  href="{{ route('reports.page') }}" class="hover:text-gray-400 text-pink-500">Reports</a>
        </div>
    </div>
</nav>

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
            <a href="#" class="btn btn-primary w-100">Download Sales Report</a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="#" class="btn btn-info w-100">Download Purchase Report</a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="#" class="btn btn-warning w-100">Download Stock Report</a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="#" class="btn btn-success w-100">Download Customer Report</a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="#" class="btn btn-dark w-100">Download Financial Report</a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="#" class="btn btn-secondary w-100">Download Inventory Report</a>
        </div>
    </div>
</div>

</body>
</html>
