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

</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-gray-800 p-3">
    <div class="flex items-center justify-between container mx-auto">
        <a href="{{ route('home') }}" class="flex items-center text-white">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="mr-3" style="height: 80px;">
        </a>
        <div class="flex space-x-6 text-white">
            <a href="{{ route('home') }}" class="hover:text-gray-400  text-pink-500">Home</a>
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-gray-400">Purchase Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="hover:text-gray-400">Invoice & Delivery</a>
            <a  href="{{ route('reports.page') }}" class="hover:text-gray-400">Reports</a>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<div class="container mt-4">
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

<!-- Billing Form -->
<div class="container mt-5">
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
                    <div class="col-md-1">
                        <label class="form-label">#</label>
                        <p class="item-number mt-2">1</p>
                    </div>
                    <div class="col-md-2">
                        <label for="item_0" class="form-label">Item</label>
                        <select class="form-select item-select" id="item_0" name="items[0][name]" required>
                            <option value="" disabled selected>Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->item_code }}" data-price="{{ $item->price }}">
                                    {{ $item->item_code }} - ${{ $item->price }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="color_0" class="form-label">Color</label>
                        <input type="text" class="form-control" id="color_0" name="items[0][color]">
                    </div>
                    <div class="col-md-1">
                        <label for="color_number_0" class="form-label">Color #</label>
                        <input type="text" class="form-control" id="color_number_0" name="items[0][color_number]">
                    </div>
                    <div class="col-md-2">
                        <label for="size_0" class="form-label">Size</label>
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
                    </div>
                    <div class="col-md-2">
                        <label for="style_0" class="form-label">Style</label>
                        <input type="text" class="form-control" id="style_0" name="items[0][style]">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-1">
                        <!-- Just for a space -->
                    </div>
                    <div class="col-md-2">
                        <label for="upc_0" class="form-label">UPC</label>
                        <input type="text" class="form-control" id="upc_0" name="items[0][upc]">
                    </div>
                    <div class="col-md-1">
                        <label for="quantity_0" class="form-label">Quantity</label>
                        <input type="number" class="form-control quantity" id="quantity_0" name="items[0][quantity]" min="1" value="1" required>
                    </div>
                    <div class="col-md-1">
                        <label for="price_0" class="form-label">Price</label>
                        <input type="text" class="form-control price" id="price_0" name="items[0][price]" readonly value="0.0000">
                    </div>
                    <div class="col-md-2">
                        <label for="more1_0" class="form-label">More 1</label>
                        <input type="text" class="form-control" id="more1_0" name="items[0][more1]">
                    </div>
                    <div class="col-md-2">
                        <label for="more2_0" class="form-label">More 2</label>
                        <input type="text" class="form-control" id="more2_0" name="items[0][more2]">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-item">Remove</button>
                    </div>
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

        $('#add-item').click(function () {
            const index = $('.item-row').length;

            // Get the last selected item's value and price
            const lastRow = $('.item-row').last();
            const lastSelectedItem = lastRow.find('.item-select option:selected');
            const lastItemCode = lastSelectedItem.val() || ''; // Fallback to empty if none selected
            const lastItemPrice = parseFloat(lastSelectedItem.data('price')) || 0;

            const newRow = `
    <div class="item-row mb-3">
        <div class="row">
            <div class="col-md-1">
                <label class="form-label">#</label>
                <p class="item-number mt-2">${index + 1}</p>
            </div>
            <div class="col-md-2">
                <label for="item_${index}" class="form-label">Item</label>
                <select class="form-select item-select" id="item_${index}" name="items[${index}][name]" required>
                    <option value="" disabled>Select Item</option>
                    @foreach($items as $item)
            <option value="{{ $item->item_code }}" data-price="{{ $item->price }}"
                        ${'{{ $item->item_code }}' === lastItemCode ? 'selected' : ''}>
                        {{ $item->item_code }} - ${{ $item->price }}
            </option>
@endforeach
            </select>
        </div>
        <div class="col-md-1">
            <label for="color_${index}" class="form-label">Color</label>
                <input type="text" class="form-control" id="color_${index}" name="items[${index}][color]">
            </div>
            <div class="col-md-1">
                <label for="color_number_${index}" class="form-label">Color #</label>
                <input type="text" class="form-control" id="color_number_${index}" name="items[${index}][color_number]">
            </div>
            <div class="col-md-2">
                <label for="size_${index}" class="form-label">Size</label>
                <select class="form-select" id="size_${index}" name="items[${index}][size]">
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
            </div>
            <div class="col-md-2">
                <label for="style_${index}" class="form-label">Style</label>
                <input type="text" class="form-control" id="style_${index}" name="items[${index}][style]">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-1">
                <!-- Just for a space -->
            </div>
            <div class="col-md-2">
                <label for="upc_${index}" class="form-label">UPC</label>
                <input type="text" class="form-control" id="upc_${index}" name="items[${index}][upc]">
            </div>
            <div class="col-md-1">
                <label for="quantity_${index}" class="form-label">Quantity</label>
                <input type="number" class="form-control quantity" id="quantity_${index}" name="items[${index}][quantity]" min="1" value="1" required>
            </div>
            <div class="col-md-1">
                <label for="price_${index}" class="form-label">Price</label>
                <input type="text" class="form-control price" id="price_${index}" name="items[${index}][price]" readonly value="${lastItemPrice.toFixed(3)}">
            </div>
            <div class="col-md-2">
                <label for="more1_${index}" class="form-label">More 1</label>
                <input type="text" class="form-control" id="more1_${index}" name="items[${index}][more1]">
            </div>
            <div class="col-md-2">
                <label for="more2_${index}" class="form-label">More 2</label>
                <input type="text" class="form-control" id="more2_${index}" name="items[${index}][more2]">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-item">Remove</button>
            </div>
        </div>
    </div>`;
            $('#items').append(newRow);
            updateItemNumbers();
            updateTotalPrice(); // Update total price
        });


        $(document).on('click', '.remove-item', function () {
            $(this).closest('.item-row').remove();
            updateItemNumbers();
            updateTotalPrice();
        });

        $(document).on('change', '.item-select', function () {
            const row = $(this).closest('.item-row');
            const selectedOption = row.find('.item-select option:selected');
            const pricePerUnit = parseFloat(selectedOption.data('price')) || 0;
            row.find('.price').val(pricePerUnit.toFixed(3));
            updateTotalPrice();
        });

        $(document).on('change', '.quantity', function () {
            const row = $(this).closest('.item-row');
            const selectedOption = row.find('.item-select option:selected');
            const pricePerUnit = parseFloat(selectedOption.data('price')) || 0;
            const quantity = parseInt(row.find('.quantity').val()) || 1;
            const totalPrice = pricePerUnit * quantity;
            row.find('.price').val(totalPrice.toFixed(3));
            updateTotalPrice();
        });

        updateItemNumbers();
    });
</script>
</body>
</html>
