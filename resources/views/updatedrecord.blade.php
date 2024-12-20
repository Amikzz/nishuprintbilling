<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Invoice Search</h2>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @elseif(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
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
