<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - InventPro Inventory Management System</title>

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
                        'soft-lg': '0 10px 30px -4px rgba(108, 77, 51, 0.08)',
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-[#FAF7F2] flex items-center justify-center p-4 font-sans text-stone-700 antialiased">

    <div class="w-full max-w-md space-y-6">

        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-coksu-500 text-white items-center justify-center shadow-md shadow-coksu-500/25">
                <i class="fa-solid fa-cookie-bite text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-800">
                Invent<span class="text-coksu-500">Pro</span>
            </h1>
            <p class="text-xs text-stone-500 font-medium">Mini Project 3: Inventory Management System (Snack Corner)</p>
        </div>

        <!-- Flash Alerts -->
        @if (session('success'))
            <div class="p-4 text-xs font-medium text-emerald-800 bg-emerald-50 rounded-2xl border border-emerald-200">
                <i class="fa-solid fa-circle-check mr-1.5 text-emerald-600"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 text-xs font-medium text-rose-800 bg-rose-50 rounded-2xl border border-rose-200">
                <i class="fa-solid fa-circle-exclamation mr-1.5 text-rose-600"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Form Card Minimalis -->
        <div class="bg-white rounded-2xl border border-[#EFE9DF] p-7 shadow-soft-lg space-y-6">
            <div>
                <h2 class="text-lg font-bold text-stone-800 tracking-tight">Selamat Datang</h2>
                <p class="text-xs text-stone-500 mt-1">Silakan masukkan email dan kata sandi untuk masuk ke sistem.</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </span>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', 'admin@inventpro.com') }}" 
                               placeholder="nama@inventpro.com" 
                               class="w-full pl-9 pr-3.5 py-2.5 text-sm rounded-xl border @error('email') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/40 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition"
                               required 
                               autofocus>
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                        Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </span>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••" 
                               class="w-full pl-9 pr-3.5 py-2.5 text-sm rounded-xl border @error('password') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/40 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition"
                               required>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center select-none cursor-pointer text-stone-600">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-coksu-500 rounded border-stone-300 focus:ring-coksu-400">
                        <span class="ml-2 font-medium">Ingat Sesi Saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-2.5 px-4 rounded-xl bg-coksu-500 hover:bg-coksu-600 text-white font-semibold text-sm shadow-md shadow-coksu-500/20 transition-all duration-150 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Masuk ke Sistem
                    </button>
                </div>
            </form>

            <!-- Akun Uji Coba Bawaan Seeder -->
            <div class="pt-4 border-t border-stone-100">
                <span class="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block mb-2">Akun Demo Pengujian:</span>
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <button type="button" 
                            onclick="document.getElementById('email').value='admin@inventpro.com'; document.getElementById('password').value='admin123';"
                            class="p-2.5 rounded-xl border border-coksu-200 bg-coksu-50 hover:bg-coksu-100 text-left transition">
                        <span class="font-bold text-coksu-800 block">Akun Admin</span>
                        <span class="text-stone-500 block">admin@inventpro.com</span>
                        <span class="font-mono text-stone-400 text-[10px] block mt-0.5">admin123 (Full CRUD)</span>
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('email').value='staff@inventpro.com'; document.getElementById('password').value='staff123';"
                            class="p-2.5 rounded-xl border border-stone-200 bg-stone-50 hover:bg-stone-100 text-left transition">
                        <span class="font-bold text-stone-800 block">Akun Staff</span>
                        <span class="text-stone-500 block">staff@inventpro.com</span>
                        <span class="font-mono text-stone-400 text-[10px] block mt-0.5">staff123 (Mutasi Stok)</span>
                    </button>
                </div>
            </div>
        </div>

        <p class="text-center text-[11px] text-stone-400">
            &copy; {{ date('Y') }} InventPro &bull; Pertemuan 5 Laravel Fundamental
        </p>
    </div>

</body>
</html>
