<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exchange Rates</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto mt-8">
    <h1 class="text-3xl font-semibold mb-4">Exchange Rate</h1>

    <!-- Display success message -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-200 text-green-600 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc pl-5 text-red-500">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Table to display exchange rates -->
    <table class="min-w-full bg-white border border-gray-200 shadow-sm rounded">
        <thead>
        <tr>
            <th class="px-4 py-2 border">Exchange Rate</th>
        </tr>
        </thead>
        <tbody>
        @foreach($exchangeRates as $exchangeRate)
            <tr>
                <td class="px-4 py-2 border">
                    <!-- Editable Rate Field -->
                    <form action="{{ route('admin.exchange.update', $exchangeRate->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')

                        <input type="number" step="0.00000001" name="rate" value="{{ $exchangeRate->rate }}"
                               class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-1 px-1 text-lg"
                               required>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-2">
                            Save
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

</body>
</html>
