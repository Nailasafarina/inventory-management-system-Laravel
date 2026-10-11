<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'InventPro') - Mini Project 3: Inventory Management System (InventPro)</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS CDN dengan Custom Palette Coksu Minimalis -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        coksu: {
                            50: '#FDFBF7',
                            100: '#F6EFE6',
                            200: '#EBDDCB',
                            300: '#D8C2A8',
                            400: '#B89878',
                            500: '#9C7554',
                            600: '#866143',
                            700: '#6C4D33',
                            800: '#523A26',
                            900: '#3A2718',
                        }
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(108, 77, 51, 0.05)',
                        'soft-lg': '0 10px 25px -3px rgba(108, 77, 51, 0.08)',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #FAF7F2;
            color: #37332F;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col font-sans antialiased text-stone-700">

    <!-- Navigation Header -->
    <header class="bg-white/95 backdrop-blur-md border-b border-[#EFE9DF] sticky top-0 z-40 transition-all shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3.5">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-11 h-11 rounded-xl bg-coksu-500 text-white flex items-center justify-center shadow-md shadow-coksu-500/20 group-hover:scale-105 transition-transform duration-200">
                            <i class="fa-solid fa-cookie-bite text-xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-lg text-stone-800 tracking-tight">Invent<span class="text-coksu-500">Pro</span></span>
                                <span class="text-[10px] uppercase font-semibold px-2 py-0.5 bg-coksu-100 text-coksu-700 rounded-full border border-coksu-200">P5 Laravel</span>
                            </div>
                            <p class="text-xs text-stone-500 tracking-normal font-medium hidden sm:block">Mini Project 3: Inventory Management System</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links Utama -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors duration-150 {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'bg-coksu-100 text-coksu-700' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100/70' }}">
                        <i class="fa-solid fa-chart-pie mr-1.5 opacity-80"></i> Dashboard
                    </a>

                    <!-- Katalog Produk -->
                    <a href="{{ route('products.index') }}" 
                       class="px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors duration-150 {{ request()->routeIs('products.*') ? 'bg-coksu-100 text-coksu-700' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100/70' }}">
                        <i class="fa-solid fa-boxes-stacked mr-1.5 opacity-80"></i> Produk
                    </a>

                    <!-- Menu Dropdown Mutasi Stok -->
                    <div class="relative group">
                        <a href="{{ route('stocks.index') }}" 
                           class="inline-flex items-center px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors duration-150 {{ request()->routeIs('stocks.*') ? 'bg-coksu-100 text-coksu-700' : 'text-stone-600 hover:text-stone-900 hover:bg-stone-100/70' }}">
                            <i class="fa-solid fa-arrow-right-arrow-left mr-1.5 opacity-80"></i> Mutasi Stok
                            <i class="fa-solid fa-chevron-down ml-1.5 text-[10px] text-stone-400 group-hover:rotate-180 transition-transform"></i>
                        </a>

                        <!-- Dropdown Menu -->
                        <div class="absolute left-0 mt-1.5 w-52 bg-white rounded-2xl border border-[#EFE9DF] shadow-soft-lg py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('stocks.index') }}" class="flex items-center px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition">
                                <i class="fa-solid fa-clock-rotate-left mr-2.5 text-stone-400 w-4"></i> Riwayat Transaksi
                            </a>
                            <div class="border-t border-stone-100 my-1"></div>
                            <a href="{{ route('stocks.create', ['type' => 'IN']) }}" class="flex items-center px-4 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-50/60 transition">
                                <i class="fa-solid fa-arrow-down mr-2.5 text-emerald-600 w-4"></i> + Catat Masuk (IN)
                            </a>
                            <a href="{{ route('stocks.create', ['type' => 'OUT']) }}" class="flex items-center px-4 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-50/60 transition">
                                <i class="fa-solid fa-arrow-up mr-2.5 text-rose-600 w-4"></i> - Catat Keluar (OUT)
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Tombol Pintas & Profil Pengguna -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <!-- Tombol Cepat Catat Masuk & Keluar di Header -->
                    <div class="flex items-center space-x-1.5 mr-1 sm:mr-2">
                        <a href="{{ route('stocks.create', ['type' => 'IN']) }}" 
                           class="inline-flex items-center px-2.5 sm:px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200/80 text-xs font-semibold transition shadow-sm"
                           title="Catat Stok Masuk (Penerimaan)">
                            <i class="fa-solid fa-arrow-down mr-1 text-[10px]"></i> <span class="hidden sm:inline">Stok</span> Masuk
                        </a>
                        <a href="{{ route('stocks.create', ['type' => 'OUT']) }}" 
                           class="inline-flex items-center px-2.5 sm:px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 text-xs font-semibold transition shadow-sm"
                           title="Catat Stok Keluar (Penjualan/Pemakaian)">
                            <i class="fa-solid fa-arrow-up mr-1 text-[10px]"></i> <span class="hidden sm:inline">Stok</span> Keluar
                        </a>
                    </div>

                    @auth
                    <!-- User Profile Info -->
                    <div class="hidden lg:flex flex-col text-right">
                        <span class="text-xs font-bold text-stone-800">{{ Auth::user()->name }}</span>
                        <div class="flex items-center justify-end space-x-1">
                            @if (Auth::user()->role === 'admin')
                                <span class="text-[10px] font-bold text-coksu-700 uppercase bg-coksu-100 px-1.5 py-0.5 rounded border border-coksu-200">Admin</span>
                            @else
                                <span class="text-[10px] font-bold text-stone-600 uppercase bg-stone-100 px-1.5 py-0.5 rounded border border-stone-200">Staff</span>
                            @endif
                            <span class="text-[10px] text-stone-400 font-mono">{{ Auth::user()->email }}</span>
                        </div>
                    </div>

                    <!-- Tombol Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-2 sm:p-2.5 rounded-xl text-stone-500 hover:text-rose-600 hover:bg-rose-50 border border-stone-200/80 transition shadow-sm"
                                title="Keluar dari Sistem (Logout)">
                            <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" 
                       class="px-4 py-2 rounded-xl bg-coksu-500 hover:bg-coksu-600 text-white font-semibold text-xs shadow-md shadow-coksu-500/20 transition">
                        <i class="fa-solid fa-lock mr-1.5"></i> Masuk
                    </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile Navigation Bar -->
            <div class="flex md:hidden items-center justify-around py-2.5 border-t border-stone-100 text-xs font-semibold">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-coksu-600' : 'text-stone-600' }}">
                    <i class="fa-solid fa-chart-pie mr-1"></i> Dashboard
                </a>
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'text-coksu-600' : 'text-stone-600' }}">
                    <i class="fa-solid fa-boxes-stacked mr-1"></i> Produk
                </a>
                <a href="{{ route('stocks.index') }}" class="{{ request()->routeIs('stocks.*') ? 'text-coksu-600' : 'text-stone-600' }}">
                    <i class="fa-solid fa-arrow-right-arrow-left mr-1"></i> Mutasi
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @include('components.alert')
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-[#EFE9DF] mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 space-y-2 sm:space-y-0">
            <div class="flex items-center space-x-2">
                <span class="font-semibold text-stone-700">InventPro</span>
                <span>&bull;</span>
                <span>Mini Project 3: Inventory Management System (Tema: Snack Corner)</span>
            </div>
            <div class="flex items-center space-x-1">
                <span>Migrasi P4 Native &rarr; P5 Laravel Framework</span>
                <span class="text-coksu-500 font-medium ml-1">&bull; Eloquent, Blade & Tailwind</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>