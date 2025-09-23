<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nisu Creation</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="fixed top-0 left-0 w-full bg-gradient-to-r from-gray-900 to-gray-800 shadow-lg z-50">
    <div class="flex items-center justify-between px-6 py-3 max-w-full mx-auto">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center text-white">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-12 w-auto mr-2 shadow-md">
            <span class="font-bold text-xl tracking-wide">Nisu Creation</span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-8 items-center text-gray-200 font-medium">
            <a href="{{ route('home') }}"
               class="transition duration-200 {{ Request::routeIs('home') ? 'text-white font-bold border-b-2 border-pink-500' : 'hover:text-white' }}">
                Home
            </a>

            <a href="{{ route('purchase-order-databases.index') }}"
               class="transition duration-200 {{ Request::routeIs('purchase-order-databases.index') ? 'text-white font-bold border-b-2 border-pink-500' : 'hover:text-white' }}">
                All Orders
            </a>

            <a href="{{ route('invoice-databases.index') }}"
               class="transition duration-200 {{ Request::routeIs('invoice-databases.index') ? 'text-white font-bold border-b-2 border-pink-500' : 'hover:text-white' }}">
                Invoice & Delivery
            </a>

            <a href="{{ route('mastersheet') }}"
               class="transition duration-200 {{ Request::routeIs('mastersheet') ? 'text-white font-bold border-b-2 border-pink-500' : 'hover:text-white' }}">
                Master Sheet
            </a>

            <a href="{{ route('urgentorders') }}"
               class="transition duration-200 {{ Request::routeIs('urgentorders') ? 'text-white font-bold border-b-2 border-pink-500' : 'hover:text-white' }}">
                Urgent Orders
            </a>

            <a href="{{ route('return.page') }}"
               class="transition duration-200 {{ Request::routeIs('return.page') ? 'text-white font-bold border-b-2 border-pink-500' : 'hover:text-white' }}">
                Returns
            </a>

            <a href="{{ route('reports.page') }}"
               class="transition duration-200 {{ Request::routeIs('reports.page') ? 'text-white font-bold border-b-2 border-pink-500' : 'hover:text-white' }}">
                Reports
            </a>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="w-full pt-20 px-6">
    @yield('content')
</div>

<script>
    // Mobile menu toggle
    const menuBtn = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>

</body>
</html>
