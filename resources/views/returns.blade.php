<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400">Invoice & Delivery</a>
            <a href="{{ route('return.page') }}" class="hover:text-gray-400  text-pink-500">Returns</a>
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
        <form id="searchForm" action="{{ route('search.returninvoice') }}" method="GET">
            @csrf
            <div class="form-group mb-3">
                <label for="invoice_number">Invoice Number</label>
                <input type="text" id="invoice_number" name="invoice_number" class="form-control" value="{{ old('invoice_number') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Return Invoice Number</button>
        </form>

        <!-- View Updated Records Button -->
        <form id="viewRecordsForm" action="{{ route('view.updated.records') }}" method="GET">
            @csrf
            <input type="hidden" id="view_invoice_number" name="invoice_number" value="">
            <button type="submit" class="btn btn-secondary mt-3">View Updated Records</button>
        </form>

        <!-- Invoice Details and Items -->
        @if (isset($returnItems))
            <div id="invoiceDetails" class="mt-4">
                <h3>Invoice Details</h3>
                {{--            <p><strong>Invoice Number:</strong> {{ $returnItems->invoice_number }}</p>--}}
                {{--            <p><strong>PO Number:</strong> {{ $returnItems->po_number }}</p>--}}

                <h4>Items</h4>
                <form action="#" method="POST">
                    @csrf
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Color Name</th>
                            <th>Color No</th>
                            <th>Size</th>
                            <th>Style</th>
                            <th>UPC No</th>
                            <th>PO Qty</th>
                            <th>Price</th>
                            <th>More1</th>
                            <th>More2</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($returnItems as $item)
                            <tr>
                                <input type="hidden" name="item_id[{{ $item->id }}]" value="{{ $item->id }}">
                                <td>{{ $item->item_code }}</td>
                                <td><input type="text" name="color_name[{{ $item->id }}]" value="{{ $item->color_name }}" class="form-control"></td>
                                <td><input type="text" name="color_no[{{ $item->id }}]" value="{{ $item->color_no }}" class="form-control"></td>
                                <td><input type="text" name="size[{{ $item->id }}]" value="{{ $item->size }}" class="form-control"></td>
                                <td><input type="text" name="style[{{ $item->id }}]" value="{{ $item->style }}" class="form-control"></td>
                                <td><input type="text" name="upc_no[{{ $item->id }}]" value="{{ $item->upc_no }}" class="form-control"></td>
                                <td><input type="number" name="po_qty[{{ $item->id }}]" value="{{ $item->po_qty }}" class="form-control"></td>
                                <td><input type="number" name="price[{{ $item->id }}]" value="{{ $item->price }}" class="form-control"></td>
                                <td><input type="text" name="more1[{{ $item->id }}]" value="{{ $item->more1 }}" class="form-control"></td>
                                <td><input type="text" name="more2[{{ $item->id }}]" value="{{ $item->more2 }}" class="form-control"></td>
                                <td>
                                    <!-- Update Button -->
                                    <form action="{{ route('update.returnitem', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT') <!-- Use PUT method for updates -->
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </form>

                                    <!-- Delete Button -->
                                    <form action="{{ route('delete.returnitem', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')  <!-- Use DELETE method for deletion -->
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </form>

                <form action="#" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-info mt-3">Create Delivery Note</button>
                </form>
            </div>
        @endif

</div>

<script>
    $(document).ready(function () {
        // When the View Updated Records button is clicked
        $('#viewRecordsForm').on('submit', function () {
            // Get the invoice number from the search form
            const invoiceNumber = $('#invoice_number').val();
            // Set the invoice number to the hidden input in the view records form
            $('#view_invoice_number').val(invoiceNumber);
        });
    });
</script>
</body>
</html>
