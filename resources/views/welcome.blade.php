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
        /* Custom widths for specific fields */
        .qty-input, .color-number-input {
            width: 90px; /* Reduce the width of Quantity and Color Number */
        }
        .upc-input {
            width: 200px; /* Increase the width of UPC */
        }
    </style>

</head>
<body>

<!-- Navbar -->
<nav class="bg-gray-800 p-3.5">
    <div class="flex items-center justify-between container-fluid">
        <a href="{{ route('home') }}" class="flex items-center text-white">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="mr-3" style="height: 80px;">
        </a>
        <div class="flex space-x-6 text-white">
            <a href="{{ route('home') }}" class="hover:text-gray-400 text-pink-500">Home</a>
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-gray-400">All Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400">Invoice & Delivery</a>
            <a href="{{route('mastersheet')}}" class="hover:text-gray-400">Master Sheet</a>
            <a href="{{route('urgentorders')}}" class="hover:text-gray-400">Urgent Orders</a>
            <a href="{{ route('return.page') }}" class="hover:text-gray-400 ">Returns</a>
            <a href="{{ route('reports.page') }}" class="hover:text-gray-400">Reports</a>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<div class="container-fluid mx-auto mt-8">

    <h1 class="text-2xl font-semibold mb-4">Purchase Order Entering</h1>

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
        @csrf
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="col-md-4">
                <label for="purchase_order_number" class="form-label">Purchase Order Number</label>
                <input type="text" class="form-control" id="purchase_order_number" name="purchase_order_number" required>
            </div>
            <div class="col-md-4">
                <label for="reference_number" class="form-label">Customer Name</label>
                <select class="form-select item-select" id="item_0" name="items[0][name]" required>
                    <option value="Star Garments (Pvt) Ltd" selected>Star Garments (Pvt) Ltd</option>
                </select>
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
                            <th>Quantity</th>
                            <th>Color No</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Style</th>
                            <th>UPC</th>
                            <th>More 1</th>
                            <th>More 2</th>
                            <th>Price</th>
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
                            <td><input type="number" class="form-control quantity qty-input" id="quantity_0" name="items[0][quantity]" min="1" required></td>
                            <td><input type="text" class="form-control qty-input" id="color_number_0" name="items[0][color_number]"></td>
                            <td><input type="text" class="form-control" id="color_0" name="items[0][color]"></td>
                            <td>
                                <input type="text" class="form-control" id="size_0" name="items[0][size]">
                            </td>
                            <td><input type="text" class="form-control" id="style_0" name="items[0][style]"></td>
                            <td><input type="text" class="form-control upc-input" id="upc_0" name="items[0][upc]"></td>
                            <td><input type="text" class="form-control" id="more1_0" name="items[0][more1]"></td>
                            <td><input type="text" class="form-control" id="more2_0" name="items[0][more2]"></td>
                            <td><input type="text" class="form-control price qty-input" id="price_0" name="items[0][price]" readonly value="0.000"></td>
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
            $('#total_price').val(total.toFixed(2));
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
            row.find('.price').val(totalItemPrice.toFixed(3));

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
            row.find('.price').val(totalItemPrice.toFixed(3));

            updateTotalPrice(); // Recalculate total price
        });

        // Add new item row
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
            <option value="{{ $item->item_code }}" data-price="{{ $item->price }} "
                            ${'{{ $item->item_code }}' === lastItemCode ? 'selected' : ''}>
                            {{ $item->item_code }} - ${{ $item->price }}
            </option>
    @endforeach
            </select></td>
                <td><input type="number" class="form-control quantity qty-input" id="quantity_${index}" name="items[${index}][quantity]" min="1" required></td>
            <td><input type="text" class="form-control qty-input" id="color_number_${index}" name="items[${index}][color_number]"></td>
            <td><input type="text" class="form-control" id="color_${index}" name="items[${index}][color]"></td>
            <td> <input type="text" class="form-control" id="size_${index}" name="items[${index}][size]"> </td>
            <td><input type="text" class="form-control" id="style_${index}" name="items[${index}][style]"></td>
            <td><input type="text" class="form-control upc-input" id="upc_${index}" name="items[${index}][upc]"></td>
            <td><input type="text" class="form-control" id="more1_${index}" name="items[${index}][more1]"></td>
            <td><input type="text" class="form-control" id="more2_${index}" name="items[${index}][more2]"></td>
            <td><input type="text" class="form-control price qty-input" id="price_${index}" name="items[${index}][price]" readonly value="${lastItemPrice.toFixed(3)}"></td>
            <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
        </tr>`;

            $('#myTable tbody').append(newRow);
            updateItemNumbers();
            updateTotalPrice(); // Update total price
        });

        // Remove item row when "Remove" button is clicked
        $(document).on('click', '.remove-item', function () {
            $(this).closest('tr').remove();
            updateItemNumbers();
            updateTotalPrice(); // Recalculate total price
        });

        // Prevent form submission on Enter and move to the next row/input field
        $(document).on('keydown', function (e) {
            let currentElement = $(':focus');
            let currentRow = currentElement.closest('tr');
            let currentColumn = currentElement.closest('td').index(); // Get column index

            if (e.key === 'Enter') {
                // Find the next input/select field in the next row
                let nextRow = currentRow.next('tr');
                if (nextRow.length) {
                    let nextElement = nextRow.find('td').eq(currentColumn).find('input, select').not(':disabled').first();
                    if (nextElement.length) {
                        nextElement.focus(); // Move focus to next element
                    }
                }
                e.preventDefault(); // Prevent form submission
            }

            if (e.key === 'ArrowDown') {
                // Get the next row in the same column
                let nextRow = currentRow.next('tr');
                if (nextRow.length) {
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

            if (e.key === 'ArrowRight') {
                // Get the next column in the same row
                let nextColumn = currentColumn + 1;
                let nextElement = currentRow.find('td').eq(nextColumn).find('input, select').not(':disabled').first();
                if (nextElement.length) {
                    nextElement.focus();
                }
                e.preventDefault();
            }

            if (e.key === 'ArrowLeft') {
                // Get the previous column in the same row
                let prevColumn = currentColumn - 1;
                let prevElement = currentRow.find('td').eq(prevColumn).find('input, select').not(':disabled').last();
                if (prevElement.length) {
                    prevElement.focus();
                }
                e.preventDefault();
            }
        });

        updateItemNumbers();
    });
</script>
</body>
</html>
