@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Welcome Header & Banner -->
    <div class="bg-white rounded-2xl border border-[#EFE9DF] p-6 sm:p-8 shadow-soft flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-1.5">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold uppercase tracking-wider text-coksu-600 bg-coksu-50 px-2.5 py-1 rounded-lg border border-coksu-200/60">
                    Ringkasan Bisnis
                </span>
                <span class="text-xs text-stone-400">&bull;</span>
                <span class="text-xs text-stone-500 font-medium">Tema: Snack Corner (Cemilan & Minuman)</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-stone-800">
                Halo, {{ Auth::user()->name }}! 👋
            </h1>
            <p class="text-xs sm:text-sm text-stone-500 max-w-2xl leading-relaxed">
                Anda masuk sebagai <strong class="text-stone-700 capitalize">{{ Auth::user()->role === 'admin' ? 'Administrator (Full Akses)' : 'Staff Gudang & Kasir' }}</strong>. Pantau perputaran stok barang, valuasi modal fisik, dan audit histori mutasi secara terpusat.
            </p>
        </div>

        <!-- Quick Action Buttons -->
        <div class="flex flex-wrap items-center gap-2.5 sm:self-center">
            <a href="{{ route('stocks.create') }}" 
               class="inline-flex items-center px-4 py-2.5 rounded-xl bg-stone-800 hover:bg-stone-900 text-white font-semibold text-xs transition shadow-sm">
                <i class="fa-solid fa-arrow-right-arrow-left mr-2 text-[11px]"></i> Catat Mutasi Stok
            </a>
            @if (Auth::user()->role === 'admin')
            <a href="{{ route('products.create') }}" 
               class="inline-flex items-center px-4 py-2.5 rounded-xl bg-coksu-500 hover:bg-coksu-600 text-white font-semibold text-xs shadow-md shadow-coksu-500/20 transition">
                <i class="fa-solid fa-plus mr-1.5 text-[11px]"></i> Tambah Produk
            </a>
            @endif
        </div>
    </div>

    <!-- 4 Key Metric Cards (Clean Coksu Dashboard) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Metric 1: Total Master Produk -->
        <div class="bg-white rounded-2xl border border-[#EFE9DF] p-5 shadow-soft hover:shadow-soft-lg transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Produk</span>
                <div class="w-9 h-9 rounded-xl bg-coksu-50 text-coksu-600 flex items-center justify-center text-sm border border-coksu-200/50">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-stone-800 tracking-tight">{{ $totalProducts }}</span>
                <span class="text-xs text-stone-400">item SKU</span>
            </div>
            <p class="mt-2 text-[11px] text-stone-400 flex items-center">
                <span>{{ $totalCategories }} kategori &bull; {{ $totalSuppliers }} supplier</span>
            </p>
        </div>

        <!-- Metric 2: Total Valuasi Aset -->
        <div class="bg-white rounded-2xl border border-[#EFE9DF] p-5 shadow-soft hover:shadow-soft-lg transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Valuasi Stok Fisik</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm border border-emerald-200/50">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-xl sm:text-2xl font-bold font-mono text-stone-800 tracking-tight">
                    Rp {{ number_format($totalValuation, 0, ',', '.') }}
                </span>
            </div>
            <p class="mt-2 text-[11px] text-stone-400">
                Nilai akumulasi stok &times; harga jual
            </p>
        </div>

        <!-- Metric 3: Total Kuantitas Fisik -->
        <div class="bg-white rounded-2xl border border-[#EFE9DF] p-5 shadow-soft hover:shadow-soft-lg transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Total Unit Fisik</span>
                <div class="w-9 h-9 rounded-xl bg-stone-100 text-stone-600 flex items-center justify-center text-sm border border-stone-200">
                    <i class="fa-solid fa-cubes"></i>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-2xl sm:text-3xl font-extrabold text-stone-800 tracking-tight">{{ $totalStockUnits }}</span>
                <span class="text-xs text-stone-400">unit barang</span>
            </div>
            <p class="mt-2 text-[11px] text-stone-400">
                Tersedia di rak dan etalase toko
            </p>
        </div>

        <!-- Metric 4: Produk Stok Kritis -->
        <div class="bg-white rounded-2xl border border-[#EFE9DF] p-5 shadow-soft hover:shadow-soft-lg transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Stok Kritis/Rendah</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm border border-amber-200/50">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-2xl sm:text-3xl font-extrabold {{ $criticalStockCount > 0 ? 'text-amber-600' : 'text-stone-800' }} tracking-tight">
                    {{ $criticalStockCount }}
                </span>
                <span class="text-xs text-stone-400">item butuh restock</span>
            </div>
            <p class="mt-2 text-[11px] {{ $criticalStockCount > 0 ? 'text-amber-600 font-medium' : 'text-stone-400' }}">
                {{ $criticalStockCount > 0 ? 'Perhatian: Ada stok di bawah batas minimal' : 'Seluruh stok dalam kondisi aman' }}
            </p>
        </div>
    </div>

    <!-- Dual Table Grid: Produk Kritis & Riwayat Mutasi Terkini -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kolom Kiri: Peringatan Stok Kritis (5 Kolom) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-[#EFE9DF] shadow-soft overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-stone-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-stone-800 flex items-center">
                            <i class="fa-solid fa-bell text-amber-500 mr-2"></i> Peringatan Stok Rendah
                        </h2>
                        <p class="text-[11px] text-stone-400 mt-0.5">Produk dengan stok &le; minimum stock</p>
                    </div>
                    <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="text-xs text-coksu-600 hover:text-coksu-800 font-medium">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="divide-y divide-stone-100 text-xs">
                    @forelse ($criticalProducts as $cp)
                    <div class="p-4 hover:bg-[#FAF7F2]/60 transition flex items-center justify-between">
                        <div>
                            <span class="font-mono text-[10px] text-stone-400 block">{{ $cp->sku }}</span>
                            <span class="font-bold text-stone-800 block text-sm mt-0.5">{{ $cp->name }}</span>
                            <span class="text-[11px] text-stone-500 block mt-0.5">{{ $cp->category->name }}</span>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                Sisa {{ $cp->stock }} (Min {{ $cp->minimum_stock }})
                            </span>
                            <a href="{{ route('stocks.create') }}" class="block text-[11px] text-coksu-600 hover:underline mt-1 font-medium">
                                + Restock Masuk
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-stone-400">
                        <i class="fa-regular fa-circle-check text-2xl text-emerald-500 mb-2 block"></i>
                        <p class="font-medium text-xs text-stone-600">Seluruh stok persediaan aman!</p>
                        <p class="text-[11px] mt-0.5">Tidak ada produk yang berada di bawah batas minimum.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-stone-50/50 border-t border-stone-100 text-center">
                <a href="{{ route('products.index') }}" class="text-xs font-semibold text-stone-600 hover:text-stone-900 transition">
                    Buka Katalog Lengkap Produk &rarr;
                </a>
            </div>
        </div>

        <!-- Kolom Kanan: 5 Mutasi Stok Terakhir (7 Kolom) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-[#EFE9DF] shadow-soft overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-stone-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-stone-800 flex items-center">
                            <i class="fa-solid fa-clock-rotate-left text-coksu-500 mr-2"></i> Mutasi Stok Terbaru
                        </h2>
                        <p class="text-[11px] text-stone-400 mt-0.5">Log audit transaksi fisik masuk dan keluar (ACID)</p>
                    </div>
                    <a href="{{ route('stocks.index') }}" class="text-xs text-coksu-600 hover:text-coksu-800 font-medium">
                        Riwayat Penuh &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-stone-50/70 border-b border-stone-200/60 text-[10px] font-semibold uppercase tracking-wider text-stone-500">
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4">Produk</th>
                                <th class="py-3 px-4 text-center">Tipe</th>
                                <th class="py-3 px-4 text-center">Jumlah</th>
                                <th class="py-3 px-4">Dicatat Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($recentMovements as $rm)
                            <tr class="hover:bg-[#FAF7F2]/60 transition">
                                <td class="py-3 px-4 text-stone-400 font-mono text-[11px] whitespace-nowrap">
                                    {{ $rm->created_at ? $rm->created_at->format('d/m H:i') : '-' }}
                                </td>
                                <td class="py-3 px-4 font-semibold text-stone-800">
                                    {{ $rm->product->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    @if ($rm->type === 'IN')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            MASUK
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            KELUAR
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-bold font-mono text-stone-800">
                                    {{ $rm->type === 'IN' ? '+' : '-' }}{{ $rm->quantity }}
                                </td>
                                <td class="py-3 px-4 text-stone-500 whitespace-nowrap">
                                    {{ $rm->user->name ?? 'Sistem' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-stone-400 italic">
                                    Belum ada transaksi mutasi tercatat.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-4 bg-stone-50/50 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500">
                <span>Akumulasi: <strong class="text-emerald-700">+{{ $totalMovementsIn }} Masuk</strong> &bull; <strong class="text-rose-700">-{{ $totalMovementsOut }} Keluar</strong></span>
                <a href="{{ route('stocks.create') }}" class="font-semibold text-coksu-700 hover:text-coksu-900 transition">
                    + Transaksi Baru &rarr;
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
