@extends('layouts.app')

@section('title', 'Riwayat Mutasi Stok')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-800">Riwayat Mutasi Stok</h1>
            <p class="text-sm text-stone-500 mt-1">Audit trail lengkap seluruh pergerakan barang masuk (IN) dan keluar (OUT) sesuai standar ACID.</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('stocks.create', ['type' => 'IN']) }}" 
               class="inline-flex items-center px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold transition shadow-sm">
                <i class="fa-solid fa-arrow-down mr-1.5 text-emerald-600"></i> + Catat Masuk
            </a>
            <a href="{{ route('stocks.create', ['type' => 'OUT']) }}" 
               class="inline-flex items-center px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-800 border border-rose-200 text-xs font-bold transition shadow-sm">
                <i class="fa-solid fa-arrow-up mr-1.5 text-rose-600"></i> - Catat Keluar
            </a>
        </div>
    </div>

    <!-- Filter & Toolbar Minimalis -->
    <div class="bg-white rounded-2xl border border-[#EFE9DF] p-5 shadow-soft">
        <form method="GET" action="{{ route('stocks.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5 items-end">
            <!-- Search Input -->
            <div class="lg:col-span-6">
                <label for="search" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-1.5">Cari Produk atau Referensi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Ketik nama produk, SKU, atau keterangan mutasi..." 
                           class="w-full pl-9 pr-3.5 py-2 text-sm rounded-xl border border-stone-200 bg-stone-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition">
                </div>
            </div>

            <!-- Filter Tipe (IN / OUT) -->
            <div class="lg:col-span-3">
                <label for="type" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-1.5">Jenis Mutasi</label>
                <select id="type" 
                        name="type" 
                        class="w-full px-3.5 py-2 text-sm rounded-xl border border-stone-200 bg-stone-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition">
                    <option value="">Semua Jenis Mutasi</option>
                    <option value="IN" {{ request('type') === 'IN' ? 'selected' : '' }}>Stok Masuk (IN)</option>
                    <option value="OUT" {{ request('type') === 'OUT' ? 'selected' : '' }}>Stok Keluar (OUT)</option>
                </select>
            </div>

            <!-- Tombol Filter -->
            <div class="lg:col-span-3 flex items-center space-x-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-stone-800 hover:bg-stone-900 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                    Terapkan
                </button>
                @if (request()->hasAny(['search', 'type']))
                    <a href="{{ route('stocks.index') }}" 
                       class="px-3 py-2 bg-stone-100 hover:bg-stone-200 text-stone-600 rounded-xl text-sm font-medium transition" 
                       title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat Mutasi Stok Modern Clean -->
    <div class="bg-white rounded-2xl border border-[#EFE9DF] shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/80 border-b border-stone-200/60 text-[11px] font-semibold uppercase tracking-wider text-stone-500">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">Waktu Transaksi</th>
                        <th class="py-3.5 px-4">SKU</th>
                        <th class="py-3.5 px-4">Produk Cemilan</th>
                        <th class="py-3.5 px-4 text-center">Jenis Mutasi</th>
                        <th class="py-3.5 px-4 text-center">Kuantitas</th>
                        <th class="py-3.5 px-4">Keterangan / Referensi</th>
                        <th class="py-3.5 px-4">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-sm">
                    @forelse ($movements as $index => $mv)
                    <tr class="hover:bg-[#FAF7F2]/60 transition-colors duration-150">
                        <!-- Nomor Urut Dinamis -->
                        <td class="py-4 px-4 text-center text-xs text-stone-400 font-mono">
                            {{ $movements->firstItem() + $index }}
                        </td>

                        <!-- Tanggal & Jam -->
                        <td class="py-4 px-4 text-xs font-mono text-stone-600 whitespace-nowrap">
                            {{ $mv->created_at ? $mv->created_at->format('d/m/Y H:i') : '-' }}
                        </td>

                        <!-- SKU Badge -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-stone-100 text-stone-700 border border-stone-200">
                                {{ $mv->product->sku ?? '-' }}
                            </span>
                        </td>

                        <!-- Nama Produk -->
                        <td class="py-4 px-4 font-semibold text-stone-800">
                            @if ($mv->product)
                                <a href="{{ route('products.show', $mv->product->id) }}" class="hover:text-coksu-600 transition">
                                    {{ $mv->product->name }}
                                </a>
                            @else
                                <span class="text-stone-400 italic">Produk Dihapus</span>
                            @endif
                        </td>

                        <!-- Tipe Badge (IN / OUT) -->
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            @if ($mv->type === 'IN')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-arrow-down mr-1.5 text-[10px]"></i> MASUK (IN)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    <i class="fa-solid fa-arrow-up mr-1.5 text-[10px]"></i> KELUAR (OUT)
                                </span>
                            @endif
                        </td>

                        <!-- Kuantitas -->
                        <td class="py-4 px-4 text-center font-bold font-mono text-base whitespace-nowrap">
                            <span class="{{ $mv->type === 'IN' ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $mv->type === 'IN' ? '+' : '-' }}{{ $mv->quantity }}
                            </span>
                        </td>

                        <!-- Catatan Referensi -->
                        <td class="py-4 px-4 text-stone-600 text-xs">
                            {{ $mv->reference_note ?: '-' }}
                        </td>

                        <!-- User Pencatat -->
                        <td class="py-4 px-4 whitespace-nowrap text-xs text-stone-500">
                            <span class="inline-flex items-center">
                                <i class="fa-regular fa-user mr-1.5 text-stone-400"></i>
                                {{ $mv->user->name ?? 'Sistem' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 px-4 text-center">
                            <div class="max-w-sm mx-auto space-y-3">
                                <div class="w-12 h-12 mx-auto rounded-full bg-stone-100 flex items-center justify-center text-stone-400">
                                    <i class="fa-solid fa-arrow-right-arrow-left text-xl"></i>
                                </div>
                                <h3 class="text-stone-700 font-semibold text-base">Belum ada data mutasi</h3>
                                <p class="text-stone-500 text-xs">Riwayat pencatatan stok masuk dan keluar akan tampil otomatis di tabel ini.</p>
                                <div class="pt-2">
                                    <a href="{{ route('stocks.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-coksu-500 hover:bg-coksu-600 text-white transition">
                                        + Catat Mutasi Baru Sekarang
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if ($movements->hasPages() || $movements->total() > 0)
        <div class="p-4 bg-stone-50/50 border-t border-stone-200/60 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-500">
            <div>
                Menampilkan <span class="font-semibold text-stone-700">{{ $movements->firstItem() ?? 0 }}</span> sampai <span class="font-semibold text-stone-700">{{ $movements->lastItem() ?? 0 }}</span> dari total <span class="font-semibold text-stone-700">{{ $movements->total() }}</span> transaksi mutasi
            </div>
            <div>
                {{ $movements->links() }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
