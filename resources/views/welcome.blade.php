<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing System</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: #f7fafc; /* Tailwind's bg-gray-100 equivalent */
        }
        .container-fluid {
            padding-left: 30px;
            padding-right: 30px;
        }
        .navbar {
            width: 100%;
        }
        .table {
            width: 100%; /* Ensure the table uses full width */
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
            <a href="{{ route('home') }}" class="hover:text-gray-400 text-pink-500">Home</a>
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-gray-400">Purchase Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400">Invoice & Delivery</a>
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
</div>

<!-- Billing Form -->
<div class="container-fluid mt-5">
    <form method="POST" action="{{ route('purchase-order-databases.store') }}">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="reference_number" class="form-label">Customer Name</label>
                <select class="form-select item-select" id="item_0" name="items[0][name]" required>
                    <option value="Star Garments (Pvt) Ltd" selected>Star Garments (Pvt) Ltd</option>
                </select>
            </div>
        </div>
        @csrf
        <!-- Reference and Purchase Order Numbers -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="reference_number" class="form-label">Reference Number</label>
                <input type="text" class="form-control" id="reference_number" name="reference_number" required>
            </div>
            <div class="col-md-6">
                <label for="purchase_order_number" class="form-label">Purchase Order Number</label>
                <input type="text" class="form-control" id="purchase_order_number" name="purchase_order_number" required>
            </div>
        </div>

        <div id="items">
            <div class="item-row mb-3">
                <div class="row">
                    <table id="myTable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th> # </th>
                            <th>Item</th>
                            <th>Color</th>
                            <th>Color No</th>
                            <th>Size</th>
                            <th>Style</th>
                            <th>UPC</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>More 1</th>
                            <th>More 2</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <select class="form-select item-select" id="item_0" name="items[0][name]" required>
                                    <option value="" disabled selected>Select Item</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->item_code }}" data-price="{{ $item->price }}">
                                            {{ $item->item_code }} - ${{ $item->price }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control" id="color_0" name="items[0][color]"></td>
                            <td><input type="text" class="form-control" id="color_number_0" name="items[0][color_number]"></td>
                            <td>
                                <select class="form-select" id="size_0" name="items[0][size]">
                                    <option value="2x2.25">2" x 2.25" (57.15x50.8mm)</option>
                                    <option value="1x1.5">1" x 1.5" (38x25mm)</option>
                                    <option value="1.5x1.5">1.5" x 1.5" (38x38mm)</option>
                                    <option value="2x1.5">2" x 1.5" (50x40mm)</option>
                                    <option value="2x1.5_2">2" x 1.5" (50x38mm)</option>
                                    <option value="2x1.75">2" x 1.75" (50x45mm)</option>
                                    <option value="2x2">2" x 2" (50x50mm)</option>
                                    <option value="3x2">3" x 2" (75x50mm)</option>
                                    <option value="3.5x1.75">3.5" x 1.75" (90x45mm)</option>
                                </select>
                            </td>
                            <td><input type="text" class="form-control" id="style_0" name="items[0][style]"></td>
                            <td><input type="text" class="form-control" id="upc_0" name="items[0][upc]"></td>
                            <td><input type="number" class="form-control quantity" id="quantity_0" name="items[0][quantity]" min="1" value="1" required></td>
                            <td><input type="text" class="form-control price" id="price_0" name="items[0][price]" readonly value="0.0000"></td>
                            <td><input type="text" class="form-control" id="more1_0" name="items[0][more1]"></td>
                            <td><input type="text" class="form-control" id="more2_0" name="items[0][more2]"></td>
                            <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary mb-3" id="add-item">Add Item</button>
        <div class="mb-3">
            <label for="total_price" class="form-label">Total Price</label>
            <input type="text" class="form-control" id="total_price" name="total_price" readonly>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>

<script>
    $(document).ready(function () {
        function updateTotalPrice() {
            let total = 0.000;
            $('.item-row').each(function () {
                const price = parseFloat($(this).find('.price').val()) || 0;
                total += price;
            });
            $('#total_price').val(total.toFixed(3));
        }

        function updateItemNumbers() {
            $('.item-row').each(function (index) {
                $(this).find('.item-number').text(index + 1);
            });
        }

        // Update price when an item is selected
        $(document).on('change', '.item-select', function () {
            const selectedOption = $(this).find('option:selected');
            const pricePerUnit = parseFloat(selectedOption.data('price')) || 0;
            const row = $(this).closest('tr');
            const quantity = parseInt(row.find('.quantity').val()) || 1;

            // Update price field based on selected item and quantity
            const totalItemPrice = pricePerUnit * quantity;
            row.find('.price').val(totalItemPrice.toFixed(4));

            updateTotalPrice(); // Recalculate total price
        });

        // Update price when quantity is changed
        $(document).on('input', '.quantity', function () {
            const row = $(this).closest('tr');
            const selectedOption = row.find('.item-select option:selected');
            const pricePerUnit = parseFloat(selectedOption.data('price')) || 0;
            const quantity = parseInt($(this).val()) || 1;

            // Update price field based on new quantity
            const totalItemPrice = pricePerUnit * quantity;
            row.find('.price').val(totalItemPrice.toFixed(4));

            updateTotalPrice(); // Recalculate total price
        });


        $('#add-item').click(function () {
            const index = $('.item-row').length;

            // Get the last selected item's value and price
            const lastRow = $('.item-row').last();
            const lastSelectedItem = lastRow.find('.item-select option:selected');
            const lastItemCode = lastSelectedItem.val() || ''; // Fallback to empty if none selected
            const lastItemPrice = parseFloat(lastSelectedItem.data('price')) || 0;

            const newRow = `
                <tr class="item-row">
                    <td class="item-number mt-2">${index + 1}</td>
                    <td><select class="form-select item-select" id="item_${index}" name="items[${index}][name]" required>
                            <option value="" disabled>Select Item</option>
                            @foreach($items as $item)
            <option value="{{ $item->item_code }}" data-price="{{ $item->price }}"
                                    ${'{{ $item->item_code }}' === lastItemCode ? 'selected' : ''}>
                                    {{ $item->item_code }} - ${{ $item->price }}
            </option>
@endforeach
            </select></td>
        <td><input type="text" class="form-control" id="color_${index}" name="items[${index}][color]"></td>
                    <td><input type="text" class="form-control" id="color_number_${index}" name="items[${index}][color_number]"></td>
                    <td><select class="form-select" id="size_${index}" name="items[${index}][size]">
                            <option value="2x2.25">2" x 2.25" (57.15x50.8mm)</option>
                            <option value="1x1.5">1" x 1.5" (38x25mm)</option>
                            <option value="1.5x1.5">1.5" x 1.5" (38x38mm)</option>
                            <option value="2x1.5">2" x 1.5" (50x40mm)</option>
                            <option value="2x1.5_2">2" x 1.5" (50x38mm)</option>
                            <option value="2x1.75">2" x 1.75" (50x45mm)</option>
                            <option value="2x2">2" x 2" (50x50mm)</option>
                            <option value="3x2">3" x 2" (75x50mm)</option>
                            <option value="3.5x1.75">3.5" x 1.75" (90x45mm)</option>
                        </select></td>
                    <td><input type="text" class="form-control" id="style_${index}" name="items[${index}][style]"></td>
                    <td><input type="text" class="form-control" id="upc_${index}" name="items[${index}][upc]"></td>
                    <td><input type="number" class="form-control quantity" id="quantity_${index}" name="items[${index}][quantity]" min="1" value="1" required></td>
                    <td><input type="text" class="form-control price" id="price_${index}" name="items[${index}][price]" readonly value="${lastItemPrice.toFixed(4)}"></td>
                    <td><input type="text" class="form-control" id="more1_${index}" name="items[${index}][more1]"></td>
                    <td><input type="text" class="form-control" id="more2_${index}" name="items[${index}][more2]"></td>
                    <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                </tr>`;

            $('#myTable tbody').append(newRow);
            updateItemNumbers();
            updateTotalPrice(); // Update total price
        });

        $(document).on('keydown', function (e) {
            let currentElement = $(':focus');
            let currentRow = currentElement.closest('tr');
            let currentColumn = currentElement.closest('td').index(); // Get column index

            if (e.key === 'ArrowDown') {
                // Get the next row in the same column
                let nextRow = currentRow.next('tr');
                if (nextRow.length) {
                    // Move the focus to the next element in the same column
                    let nextElement = nextRow.find('td').eq(currentColumn).find('input, select').not(':disabled').first();
                    if (nextElement.length) {
                        nextElement.focus();
                    }
                }
                e.preventDefault();
            }

            if (e.key === 'ArrowUp') {
                // Get the previous row in the same column
                let prevRow = currentRow.prev('tr');
                if (prevRow.length) {
                    let prevElement = prevRow.find('td').eq(currentColumn).find('input, select').not(':disabled').last();
                    if (prevElement.length) {
                        prevElement.focus();
                    }
                }
                e.preventDefault();
            }
        });

        updateItemNumbers();
    });
</script>
</body>
</html>
