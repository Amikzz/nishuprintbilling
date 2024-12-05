<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" aria-current="page" href="{{route('home')}}">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="me-2" style="height: 80px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="navbar-brand d-flex align-items-center" aria-current="page" href="{{route('home')}}">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('purchase-order-databases.index')}}">Purchase Orders</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Purchase Orders</h1>

    <!-- Search Form -->
    <form action="{{ route('purchase-order-databases.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Reference No, PO No, or Item Code" value="{{ $search ?? '' }}">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Display Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Purchase Orders Table -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Reference No</th>
            <th>PO No</th>
            <th>Item Code</th>
            <th>Color Name</th>
            <th>Color No</th>
            <th>Size</th>
            <th>Style</th>
            <th>UPC No</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse($purchaseOrders as $order)
            <tr>
                <td>{{ $loop->iteration + ($purchaseOrders->currentPage() - 1) * $purchaseOrders->perPage() }}</td>
                <td>{{ $order->date }}</td>
                <td>{{ $order->reference_no }}</td>
                <td>{{ $order->po_no }}</td>
                <td>{{ $order->item_code }}</td>
                <td>{{ $order->color_name ?? '-' }}</td>
                <td>{{ $order->color_no ?? '-' }}</td>
                <td>{{ $order->size ?? '-' }}</td>
                <td>{{ $order->style ?? '-' }}</td>
                <td>{{ $order->upc_no ?? '-' }}</td>
                <td>{{ $order->po_qty }}</td>
                <td>{{ $order->price }}</td>
                <td>{{ $order->status }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="13" class="text-center">No purchase orders found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $purchaseOrders->links('pagination::bootstrap-5') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
