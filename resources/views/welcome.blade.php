<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" aria-current="page" href="{{route('home')}}">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="me-2" style="height: 100px;">
            <span><h2>Nishu Creation (Pvt) Ltd</h2></span>
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
                    <a class="nav-link" href="#">Previous Bills</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Billing Form -->
<div class="container mt-5">
    <form method="POST">
        @csrf
        <div class="mb-3">
            <label for="seller_name" class="form-label">Seller Name</label>
            <input type="text" class="form-control" id="seller_name" name="seller_name" required>
        </div>
        <div id="items">
            <div class="item-row mb-3">
                <div class="row">
                    <div class="col-md-1">
                        <label class="form-label">#</label>
                        <p class="item-number mt-2">1</p>
                    </div>
                    <div class="col-md-4">
                        <label for="item_0" class="form-label">Item</label>
                        <select class="form-select item-select" id="item_0" name="items[0][name]" required>
                            <option value="">Select Item</option>
                            <option value="Item A" data-price="100">Item A - $100</option>
                            <option value="Item B" data-price="200">Item B - $200</option>
                            <option value="Item C" data-price="150">Item C - $150</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="quantity_0" class="form-label">Quantity</label>
                        <input type="number" class="form-control quantity" id="quantity_0" name="items[0][quantity]" min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label for="price_0" class="form-label">Price</label>
                        <input type="text" class="form-control price" id="price_0" name="items[0][price]" readonly>
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
            let total = 0;
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

        $(document).on('change', '.item-select, .quantity', function () {
            const row = $(this).closest('.item-row');
            const selectedOption = row.find('.item-select option:selected');
            const pricePerUnit = parseFloat(selectedOption.data('price')) || 0;
            const quantity = parseInt(row.find('.quantity').val()) || 1;
            const totalPrice = pricePerUnit * quantity;
            row.find('.price').val(totalPrice.toFixed(2));
            updateTotalPrice();
        });

        $('#add-item').click(function () {
            const index = $('.item-row').length;
            const newRow = `
                <div class="item-row mb-3">
                    <div class="row">
                        <div class="col-md-1">
                            <label class="form-label">#</label>
                            <p class="item-number mt-2">${index + 1}</p>
                        </div>
                        <div class="col-md-4">
                            <label for="item_${index}" class="form-label">Item</label>
                            <select class="form-select item-select" id="item_${index}" name="items[${index}][name]" required>
                                <option value="">Select Item</option>
                                <option value="Item A" data-price="100">Item A - $100</option>
                                <option value="Item B" data-price="200">Item B - $200</option>
                                <option value="Item C" data-price="150">Item C - $150</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="quantity_${index}" class="form-label">Quantity</label>
                            <input type="number" class="form-control quantity" id="quantity_${index}" name="items[${index}][quantity]" min="1" value="1" required>
                        </div>
                        <div class="col-md-3">
                            <label for="price_${index}" class="form-label">Price</label>
                            <input type="text" class="form-control price" id="price_${index}" name="items[${index}][price]" readonly>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-item">Remove</button>
                        </div>
                    </div>
                </div>`;
            $('#items').append(newRow);
        });

        $(document).on('click', '.remove-item', function () {
            $(this).closest('.item-row').remove();
            updateItemNumbers();
            updateTotalPrice();
        });

        updateItemNumbers();
    });
</script>
</body>
</html>
