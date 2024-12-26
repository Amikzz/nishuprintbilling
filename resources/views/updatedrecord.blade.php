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

    <h1>Previous D-Note Number = {{$d_note_no}}</h1>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @elseif(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (isset($returnItems))
        <div id="invoiceDetails" class="mt-4">
            <h3>Invoice Details</h3>

            <h4>Items</h4>
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
                        <form action="{{ route('update.returnitem', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <td>{{ $item->item_code }}</td>
                            <td><input type="text" name="color_name" value="{{ $item->color_name }}" class="form-control"></td>
                            <td><input type="text" name="color_no" value="{{ $item->color_no }}" class="form-control"></td>
                            <td><input type="text" name="size" value="{{ $item->size }}" class="form-control"></td>
                            <td><input type="text" name="style" value="{{ $item->style }}" class="form-control"></td>
                            <td><input type="text" name="upc_no" value="{{ $item->upc_no }}" class="form-control"></td>
                            <td>
                                <input type="number" name="po_qty" value="{{ $item->po_qty }}" class="form-control qty-input" data-item-id="{{ $item->id }}">
                            </td>
                            <td>
                                <input type="text" name="price" value="{{ $item->price }}" class="form-control price-input" readonly id="price-{{ $item->id }}">
                            </td>
                            <td><input type="text" name="more1" value="{{ $item->more1 }}" class="form-control"></td>
                            <td><input type="text" name="more2" value="{{ $item->more2 }}" class="form-control"></td>
                            <td>
                                <button type="submit" class="btn btn-success update-button" data-item-id="{{ $item->id }}">Update</button>
                        </form>
                        <form action="{{ route('delete.returnitem', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!-- Button to trigger modal -->
            <button type="button" class="btn btn-info mt-3" data-bs-toggle="modal" data-bs-target="#deliveryNoteModal">
                Create Delivery Note
            </button>

            <form action="{{ route('return.page') }}" method="GET" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary mt-3">Cancel</button>
            </form>

        </div>
    @endif
</div>

<!-- Modal -->
<div class="modal fade" id="deliveryNoteModal" tabindex="-1" aria-labelledby="deliveryNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('deliverynote.return', $d_note_no)}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="deliveryNoteModalLabel">New Delivery Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_dnote_no" class="form-label">Enter New Delivery Note Number</label>
                        <input type="text" name="new_dnote_no" id="new_dnote_no" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Delivery Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        const unitPrices = {};

        $('.qty-input').each(function () {
            const itemId = $(this).data('item-id');
            const qty = parseFloat($(this).val());
            const price = parseFloat($(`#price-${itemId}`).val());
            if (qty > 0) {
                unitPrices[itemId] = price / qty;
            }
        });

        $('.qty-input').on('input', function () {
            const itemId = $(this).data('item-id');
            const qty = parseFloat($(this).val()) || 0;
            const unitPrice = unitPrices[itemId] || 0;
            const newPrice = (qty * unitPrice).toFixed(2);
            $(`#price-${itemId}`).val(newPrice);
        });
    });
</script>
</body>
</html>
