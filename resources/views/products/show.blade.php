@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Breadcrumb & Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-2 text-xs text-stone-500 font-medium">
            <a href="{{ route('products.index') }}" class="hover:text-coksu-600 transition flex items-center">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Katalog
            </a>
            <span>/</span>
            <span class="text-stone-800 font-semibold">Detail Produk</span>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Tombol Cepat Mutasi Stok untuk Produk Ini -->
            <a href="{{ route('stocks.create', ['product_id' => $product->id, 'type' => 'IN']) }}" 
               class="inline-flex items-center px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 transition shadow-xs">
                <i class="fa-solid fa-arrow-down mr-1.5 text-emerald-600"></i> + Catat Masuk
            </a>
            <a href="{{ route('stocks.create', ['product_id' => $product->id, 'type' => 'OUT']) }}" 
               class="inline-flex items-center px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-800 font-bold text-xs border border-rose-200 transition shadow-xs">
                <i class="fa-solid fa-arrow-up mr-1.5 text-rose-600"></i> - Catat Keluar
            </a>

            @if (Auth::user() && Auth::user()->role === 'admin')
            <a href="{{ route('products.edit', $product->id) }}" 
               class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-semibold text-xs border border-amber-200 transition">
                <i class="fa-regular fa-pen-to-square mr-1.5"></i> Edit Produk
            </a>
            @endif
        </div>
    </div>

    <!-- Product Summary Card Minimalis -->
    <div class="bg-white rounded-2xl border border-[#EFE9DF] p-6 sm:p-8 shadow-soft">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-6 border-b border-stone-100">
            <div>
                <div class="flex items-center space-x-2.5">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-medium bg-stone-100 text-stone-700 border border-stone-200">
                        {{ $product->sku }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-coksu-100 text-coksu-700 border border-coksu-200">
                        {{ $product->category->name }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-stone-800 mt-2">{{ $product->name }}</h1>
                <p class="text-xs text-stone-400 mt-1">Ditambahkan pada: {{ $product->created_at ? $product->created_at->format('d M Y, H:i') : '-' }}</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-stone-400 uppercase tracking-wider font-semibold block">Harga Jual</span>
                <span class="text-2xl font-bold font-mono text-coksu-700">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6">
            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200/70">
                <span class="text-xs text-stone-500 font-medium block">Stok Fisik Tersedia</span>
                <div class="flex items-baseline space-x-2 mt-1">
                    <span class="text-2xl font-bold text-stone-800 font-mono">{{ $product->stock }}</span>
                    <span class="text-xs text-stone-400">unit</span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200/70">
                <span class="text-xs text-stone-500 font-medium block">Batas Minimum Stok</span>
                <div class="flex items-baseline space-x-2 mt-1">
                    <span class="text-2xl font-bold text-stone-800 font-mono">{{ $product->minimum_stock }}</span>
                    <span class="text-xs text-stone-400">unit</span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-stone-50 border border-stone-200/70">
                <span class="text-xs text-stone-500 font-medium block">Status Persediaan</span>
                <div class="mt-1">
                    @if ($product->stock <= 0)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            Stok Habis
                        </span>
                    @elseif ($product->stock <= $product->minimum_stock)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            Peringatan: Stok Rendah
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Stok Aman
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Card -->
    <div class="bg-white rounded-2xl border border-[#EFE9DF] p-6 shadow-soft space-y-4">
        <h2 class="text-sm font-bold uppercase tracking-wider text-coksu-700 pb-2 border-b border-stone-100 flex items-center">
            <i class="fa-solid fa-truck-ramp-box mr-2 text-xs"></i> Rekanan Pemasok (Suppliers)
        </h2>
        @if ($product->suppliers->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($product->suppliers as $sup)
                    <div class="p-3.5 rounded-xl border border-stone-200 bg-stone-50/50 flex justify-between items-center text-xs">
                        <div>
                            <span class="font-bold text-stone-800 block">{{ $sup->name }}</span>
                            <span class="text-stone-500 block mt-0.5">PIC: {{ $sup->contact_person ?? '-' }} ({{ $sup->phone ?? '-' }})</span>
                        </div>
                        @if ($sup->pivot->supplier_price)
                            <div class="text-right">
                                <span class="text-[10px] text-stone-400 block uppercase">Harga Beli</span>
                                <span class="font-mono font-semibold text-stone-700">Rp {{ number_format($sup->pivot->supplier_price, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-stone-400 italic">Produk ini belum dihubungkan ke rekanan supplier manapun.</p>
        @endif
    </div>

    <!-- Histori Mutasi Stok (Stock Movements) -->
    <div class="bg-white rounded-2xl border border-[#EFE9DF] shadow-soft overflow-hidden">
        <div class="p-6 border-b border-stone-100 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-coksu-700 flex items-center">
                    <i class="fa-solid fa-clock-rotate-left mr-2 text-xs"></i> Riwayat Audit Mutasi Stok Produk Ini
                </h2>
                <p class="text-xs text-stone-400 mt-1">Audit log pergerakan stok masuk dan keluar khusus untuk produk {{ $product->name }}.</p>
            </div>
            <a href="{{ route('stocks.create', ['product_id' => $product->id]) }}" class="text-xs font-semibold text-coksu-600 hover:text-coksu-800 transition">
                + Tambah Transaksi Mutasi &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/80 border-b border-stone-200/60 text-[11px] font-semibold uppercase tracking-wider text-stone-500">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4 text-center">Tipe</th>
                        <th class="py-3 px-4 text-center">Kuantitas</th>
                        <th class="py-3 px-4">Catatan Referensi</th>
                        <th class="py-3 px-4">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-xs">
                    @forelse ($product->stockMovements()->latest()->get() as $mv)
                    <tr class="hover:bg-[#FAF7F2]/60">
                        <td class="py-3 px-4 text-stone-500 whitespace-nowrap font-mono">
                            {{ $mv->created_at ? $mv->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            @if ($mv->type === 'IN')
                                <span class="inline-flex items-center px-2 py-0.5 rounded font-bold text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-arrow-down mr-1"></i> MASUK (IN)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded font-bold text-[10px] bg-rose-50 text-rose-700 border border-rose-200">
                                    <i class="fa-solid fa-arrow-up mr-1"></i> KELUAR (OUT)
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center font-bold font-mono text-stone-800">
                            {{ $mv->type === 'IN' ? '+' : '-' }}{{ $mv->quantity }}
                        </td>
                        <td class="py-3 px-4 text-stone-600">
                            {{ $mv->reference_note ?: '-' }}
                        </td>
                        <td class="py-3 px-4 text-stone-500 whitespace-nowrap">
                            {{ $mv->user->name ?? 'Sistem' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 px-4 text-center text-xs text-stone-400 italic">
                            Belum ada riwayat pergerakan stok untuk produk ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
