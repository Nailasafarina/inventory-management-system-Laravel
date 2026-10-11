@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action Buttons -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-stone-800">Katalog Produk Cemilan</h1>
            <p class="text-sm text-stone-500 mt-1">Kelola daftar produk, harga jual, rekanan supplier, dan catat mutasi stok fisik secara langsung.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Tombol Cepat Mutasi Stok -->
            <a href="{{ route('stocks.create', ['type' => 'IN']) }}" 
               class="inline-flex items-center px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold transition shadow-sm">
                <i class="fa-solid fa-arrow-down mr-1.5 text-emerald-600"></i> + Catat Masuk
            </a>
            <a href="{{ route('stocks.create', ['type' => 'OUT']) }}" 
               class="inline-flex items-center px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-800 border border-rose-200 text-xs font-bold transition shadow-sm">
                <i class="fa-solid fa-arrow-up mr-1.5 text-rose-600"></i> - Catat Keluar
            </a>

            <!-- Tombol Tambah Produk (Khusus Admin) -->
            @if (Auth::user() && Auth::user()->role === 'admin')
            <a href="{{ route('products.create') }}" 
               class="inline-flex items-center px-4 py-2 rounded-xl bg-coksu-500 hover:bg-coksu-600 text-white font-semibold text-xs shadow-md shadow-coksu-500/20 transition-all duration-150 transform hover:-translate-y-0.5">
                <i class="fa-solid fa-plus mr-1.5"></i> Tambah Produk Baru
            </a>
            @endif
        </div>
    </div>

    <!-- Filter & Search Toolbar (Card Minimalis) -->
    <div class="bg-white rounded-2xl border border-[#EFE9DF] p-5 shadow-soft">
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5 items-end">
            <!-- Input Pencarian -->
            <div class="lg:col-span-4">
                <label for="search" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-1.5">Cari Produk / SKU</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Ketik nama camilan atau kode SKU..." 
                           class="w-full pl-9 pr-3.5 py-2 text-sm rounded-xl border border-stone-200 bg-stone-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition">
                </div>
            </div>

            <!-- Filter Kategori -->
            <div class="lg:col-span-3">
                <label for="category_id" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-1.5">Kategori</label>
                <select id="category_id" 
                        name="category_id" 
                        class="w-full px-3.5 py-2 text-sm rounded-xl border border-stone-200 bg-stone-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sorting Kolom -->
            <div class="lg:col-span-2">
                <label for="sort_by" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-1.5">Urutkan</label>
                <select id="sort_by" 
                        name="sort_by" 
                        class="w-full px-3.5 py-2 text-sm rounded-xl border border-stone-200 bg-stone-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition">
                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama Produk</option>
                    <option value="sku" {{ request('sort_by') == 'sku' ? 'selected' : '' }}>SKU</option>
                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Harga</option>
                    <option value="stock" {{ request('sort_by') == 'stock' ? 'selected' : '' }}>Stok</option>
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                </select>
            </div>

            <!-- Sorting Arah -->
            <div class="lg:col-span-1">
                <label for="sort_direction" class="block text-xs font-semibold text-stone-600 uppercase tracking-wider mb-1.5">Arah</label>
                <select id="sort_direction" 
                        name="sort_direction" 
                        class="w-full px-2.5 py-2 text-sm rounded-xl border border-stone-200 bg-stone-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition">
                    <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>A &rarr; Z</option>
                    <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Z &rarr; A</option>
                </select>
            </div>

            <!-- Tombol Aksi Filter & Reset -->
            <div class="lg:col-span-2 flex items-center space-x-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-stone-800 hover:bg-stone-900 text-white rounded-xl text-sm font-semibold transition shadow-sm">
                    Terapkan
                </button>
                @if (request()->hasAny(['search', 'category_id', 'sort_by', 'sort_direction', 'stock_status']))
                    <a href="{{ route('products.index') }}" 
                       class="px-3 py-2 bg-stone-100 hover:bg-stone-200 text-stone-600 rounded-xl text-sm font-medium transition" 
                       title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Data Produk Modern Clean -->
    <div class="bg-white rounded-2xl border border-[#EFE9DF] shadow-soft overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/80 border-b border-stone-200/60 text-[11px] font-semibold uppercase tracking-wider text-stone-500">
                        <th class="py-3.5 px-4 text-center w-12">No</th>
                        <th class="py-3.5 px-4">SKU</th>
                        <th class="py-3.5 px-4">Nama Produk</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4 text-right">Harga Jual</th>
                        <th class="py-3.5 px-4 text-center">Stok Fisik & Aksi Cepat</th>
                        <th class="py-3.5 px-4">Supplier</th>
                        <th class="py-3.5 px-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-sm">
                    @forelse ($products as $index => $product)
                    <tr class="hover:bg-[#FAF7F2]/60 transition-colors duration-150">
                        <!-- Nomor Urut Dinamis dengan Pagination -->
                        <td class="py-4 px-4 text-center text-xs text-stone-400 font-mono">
                            {{ $products->firstItem() + $index }}
                        </td>

                        <!-- SKU Badge -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-medium bg-stone-100 text-stone-700 border border-stone-200/80">
                                {{ $product->sku }}
                            </span>
                        </td>

                        <!-- Nama Produk -->
                        <td class="py-4 px-4 font-semibold text-stone-800">
                            <a href="{{ route('products.show', $product->id) }}" class="hover:text-coksu-500 transition-colors">
                                {{ $product->name }}
                            </a>
                        </td>

                        <!-- Kategori Badge Coksu Minimalis -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-coksu-100 text-coksu-700 border border-coksu-200">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>

                        <!-- Harga Format Rupiah -->
                        <td class="py-4 px-4 text-right font-mono font-medium text-stone-800 whitespace-nowrap">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>

                        <!-- Stok Fisik & Tombol Aksi Cepat Masuk / Keluar -->
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Tombol Cepat Stok Masuk (+) -->
                                <a href="{{ route('stocks.create', ['product_id' => $product->id, 'type' => 'IN']) }}" 
                                   class="w-7 h-7 rounded-lg bg-emerald-50 hover:bg-emerald-500 text-emerald-700 hover:text-white border border-emerald-200 flex items-center justify-center text-xs font-bold transition shadow-xs" 
                                   title="Catat Stok Masuk untuk {{ $product->name }}">
                                    <i class="fa-solid fa-plus"></i>
                                </a>

                                <!-- Kuantitas Stok & Status -->
                                <div class="inline-flex flex-col items-center min-w-[70px]">
                                    <span class="font-extrabold text-stone-800 text-sm font-mono">{{ $product->stock }}</span>
                                    @if ($product->stock <= 0)
                                        <span class="text-[9px] font-bold text-rose-700 uppercase">Habis</span>
                                    @elseif ($product->stock <= $product->minimum_stock)
                                        <span class="text-[9px] font-bold text-amber-700 uppercase" title="Batas minimum: {{ $product->minimum_stock }}">
                                            Low (Min {{ $product->minimum_stock }})
                                        </span>
                                    @else
                                        <span class="text-[9px] font-medium text-emerald-700 uppercase">Aman</span>
                                    @endif
                                </div>

                                <!-- Tombol Cepat Stok Keluar (-) -->
                                <a href="{{ route('stocks.create', ['product_id' => $product->id, 'type' => 'OUT']) }}" 
                                   class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-500 text-rose-700 hover:text-white border border-rose-200 flex items-center justify-center text-xs font-bold transition shadow-xs" 
                                   title="Catat Stok Keluar untuk {{ $product->name }}">
                                    <i class="fa-solid fa-minus"></i>
                                </a>
                            </div>
                        </td>

                        <!-- Supplier Relasi Many-to-Many -->
                        <td class="py-4 px-4">
                            @if ($product->suppliers->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($product->suppliers as $supplier)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-stone-100 text-stone-600 border border-stone-200" title="Harga pasok: Rp {{ number_format($supplier->pivot->supplier_price ?? 0, 0, ',', '.') }}">
                                            {{ $supplier->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-stone-400 italic">Belum ada supplier</span>
                            @endif
                        </td>

                        <!-- Tombol Aksi Detail / Edit / Hapus -->
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <a href="{{ route('products.show', $product->id) }}" 
                                   class="p-2 rounded-xl text-stone-500 hover:text-stone-800 hover:bg-stone-100 transition" 
                                   title="Detail Produk">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </a>

                                @if (Auth::user() && Auth::user()->role === 'admin')
                                <a href="{{ route('products.edit', $product->id) }}" 
                                   class="p-2 rounded-xl text-amber-600 hover:text-amber-800 hover:bg-amber-50 transition" 
                                   title="Ubah Data">
                                    <i class="fa-regular fa-pen-to-square text-sm"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $product->name }}? Data mutasi terkait akan dicek integritasnya.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 rounded-xl text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition" 
                                            title="Hapus Produk">
                                        <i class="fa-regular fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 px-4 text-center">
                            <div class="max-w-sm mx-auto space-y-3">
                                <div class="w-12 h-12 mx-auto rounded-full bg-stone-100 flex items-center justify-center text-stone-400">
                                    <i class="fa-solid fa-box-open text-xl"></i>
                                </div>
                                <h3 class="text-stone-700 font-semibold text-base">Tidak ada produk ditemukan</h3>
                                <p class="text-stone-500 text-xs">Coba sesuaikan kata kunci pencarian atau reset filter untuk melihat seluruh katalog produk.</p>
                                @if (request()->hasAny(['search', 'category_id', 'sort_by', 'sort_direction']))
                                    <div class="pt-2">
                                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold bg-stone-800 text-white hover:bg-stone-900 transition">
                                            Reset Semua Filter
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Info Bawah -->
        @if ($products->hasPages() || $products->total() > 0)
        <div class="p-4 bg-stone-50/50 border-t border-stone-200/60 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-500">
            <div>
                Menampilkan <span class="font-semibold text-stone-700">{{ $products->firstItem() ?? 0 }}</span> sampai <span class="font-semibold text-stone-700">{{ $products->lastItem() ?? 0 }}</span> dari total <span class="font-semibold text-stone-700">{{ $products->total() }}</span> produk
            </div>
            <div>
                {{ $products->links() }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
