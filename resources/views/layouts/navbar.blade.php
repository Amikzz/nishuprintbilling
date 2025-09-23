<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nisu Creation</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            <a href="{{ route('home') }}" class="hover:text-white transition duration-200">Home</a>
            <a href="{{ route('purchase-order-databases.index') }}" class="hover:text-white transition duration-200">All Orders</a>
            <a href="{{ route('invoice-databases.index') }}" class="text-pink-400 font-semibold hover:text-pink-300 transition duration-200">Invoice & Delivery</a>
            <a href="{{ route('mastersheet') }}" class="hover:text-white transition duration-200">Master Sheet</a>
            <a href="{{ route('urgentorders') }}" class="hover:text-white transition duration-200">Urgent Orders</a>
            <a href="{{ route('return.page') }}" class="hover:text-white transition duration-200">Returns</a>
            <a href="{{ route('reports.page') }}" class="hover:text-white transition duration-200">Reports</a>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button id="menu-btn" class="text-white focus:outline-none">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-gray-800 px-6 pb-4 space-y-2">
        <a href="{{ route('home') }}" class="block text-gray-200 hover:text-white">Home</a>
        <a href="{{ route('purchase-order-databases.index') }}" class="block text-gray-200 hover:text-white">All Orders</a>
        <a href="{{ route('invoice-databases.index') }}" class="block text-pink-400 font-semibold hover:text-pink-300">Invoice & Delivery</a>
        <a href="{{ route('mastersheet') }}" class="block text-gray-200 hover:text-white">Master Sheet</a>
        <a href="{{ route('urgentorders') }}" class="block text-gray-200 hover:text-white">Urgent Orders</a>
        <a href="{{ route('return.page') }}" class="block text-gray-200 hover:text-white">Returns</a>
        <a href="{{ route('reports.page') }}" class="block text-gray-200 hover:text-white">Reports</a>
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
