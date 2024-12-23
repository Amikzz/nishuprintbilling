<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold text-center mb-6">Edit Invoice</h1>

    <!-- Form for editing the invoice details -->
    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Invoice Information -->
        <div class="mb-4 flex justify-between gap-4">
            <div class="w-full">
                <label for="invoice_no" class="block text-sm font-medium text-gray-700">Invoice No</label>
                <input type="text" id="invoice_no" name="invoice_no" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $invoice->invoice_no }}" readonly>
            </div>

            <div class="w-full">
                <label for="reference_no" class="block text-sm font-medium text-gray-700">Reference No</label>
                <input type="text" id="reference_no" name="reference_no" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $invoice->reference_no }}" readonly>
            </div>
        </div>

        <!-- Loop through the items to allow editing -->
        <h2 class="mt-6 text-xl font-semibold mb-4">Items</h2>
        <div class="space-y-4" id="items">
            @foreach($items as $index => $item)
                <div class="bg-gray-100 p-4 rounded-lg item-row flex items-center space-x-4" data-index="{{ $index }}">

                    <!-- Item details -->
                    <div class="w-1/12">
                        <label for="id_{{ $index }}" class="block text-sm font-medium text-gray-700">ID</label>
                        <input type="text" id="id_{{ $index }}" name="items[{{ $index }}][id]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['id'] }}" readonly>
                    </div>

                    <div class="w-1/12">
                        <label for="item_code_{{ $index }}" class="block text-sm font-medium text-gray-700">Item Code</label>
                        <input type="text" id="item_code_{{ $index }}" name="items[{{ $index }}][item_code]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['item_code'] }}" readonly>
                    </div>

                    <div class="w-1/12">
                        <label for="item_name_{{ $index }}" class="block text-sm font-medium text-gray-700">Item Name</label>
                        <input type="text" id="item_name_{{ $index }}" name="items[{{ $index }}][item_name]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['item_name'] }}" readonly>
                    </div>

                    <div class="w-1/12">
                        <label for="color_{{ $index }}" class="block text-sm font-medium text-gray-700">Color</label>
                        <input type="text" id="color_{{ $index }}" name="items[{{ $index }}][color]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['color'] }}">
                    </div>

                    <div class=w-1/12">
                        <label for="color_number_{{ $index }}" class="block text-sm font-medium text-gray-700">Color No</label>
                        <input type="text" id="color_number_{{ $index }}" name="items[{{ $index }}][color_number]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['color_no'] }}">
                    </div>

                    <div class="w-1/12">
                        <label for="size_{{ $index }}" class="block text-sm font-medium text-gray-700">Size</label>
                        <input type="text" id="size_{{ $index }}" name="items[{{ $index }}][size]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['size'] }}">
                    </div>

                    <div class="w-1/12">
                        <label for="style_{{ $index }}" class="block text-sm font-medium text-gray-700">Style</label>
                        <input type="text" id="style_{{ $index }}" name="items[{{ $index }}][style]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['style'] }}">
                    </div>

                    <div class="w-1/12">
                        <label for="upc_{{ $index }}" class="block text-sm font-medium text-gray-700">UPC</label>
                        <input type="text" id="upc_{{ $index }}" name="items[{{ $index }}][upc]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['upc'] }}">
                    </div>

                    <div class="w-1/12">
                        <label for="more1_{{ $index }}" class="block text-sm font-medium text-gray-700">More 1</label>
                        <input type="text" id="more1_{{ $index }}" name="items[{{ $index }}][more1]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['more1'] }}">
                    </div>

                    <div class="w-1/12">
                        <label for="quantity_{{ $index }}" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" id="quantity_{{ $index }}" name="items[{{ $index }}][po_qty]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['po_qty'] }}" oninput="updateTotalPrice({{ $index }})">
                    </div>

                    <div class="w-1/12">
                        <label for="unit_price_{{ $index }}" class="block text-sm font-medium text-gray-700">Unit Price</label>
                        <input type="number" id="unit_price_{{ $index }}" name="items[{{ $index }}][unit_price]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['unit_price'] }}" readonly>
                    </div>

                    <div class="w-1/12">
                        <label for="total_price_{{ $index }}" class="block text-sm font-medium text-gray-700">Total Price</label>
                        <input type="number" id="total_price_{{ $index }}" name="items[{{ $index }}][price]" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ $item['price'] }}" readonly>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-between">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Changes</button>
            <a href="{{ route('invoice-databases.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
        </div>
    </form>
</div>

<script>
    function updateTotalPrice(index) {
        // Get the quantity and unit price values
        const quantity = parseFloat(document.getElementById('quantity_' + index).value) || 0;
        const unitPrice = parseFloat(document.getElementById('unit_price_' + index).value) || 0;

        // Calculate the total price
        const totalPrice = quantity * unitPrice;

        // Update the total price field
        document.getElementById('total_price_' + index).value = totalPrice.toFixed(3);
    }
</script>

</body>
</html>
