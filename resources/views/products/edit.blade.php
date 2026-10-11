@extends('layouts.app')

@section('title', 'Ubah Produk: ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-stone-500 font-medium">
            <a href="{{ route('products.index') }}" class="hover:text-coksu-600 transition flex items-center">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Katalog
            </a>
            <span>/</span>
            <span class="text-stone-800 font-semibold">Ubah Produk</span>
        </div>
        <span class="text-xs font-mono font-medium px-2.5 py-1 rounded-lg bg-stone-100 text-stone-600 border border-stone-200">
            ID: #{{ $product->id }}
        </span>
    </div>

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-stone-800">Ubah Data Produk</h1>
        <p class="text-sm text-stone-500 mt-1">Perbarui informasi master produk, harga, batas minimum stok, atau rekanan pemasok.</p>
    </div>

    <!-- Form Card Minimalis -->
    <div class="bg-white rounded-2xl border border-[#EFE9DF] p-6 sm:p-8 shadow-soft">
        <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Informasi Master Produk -->
            <div>
                <h2 class="text-sm font-bold uppercase tracking-wider text-coksu-700 pb-2 border-b border-stone-100 flex items-center">
                    <i class="fa-solid fa-tag mr-2 text-xs"></i> 1. Informasi Master Produk
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                            Kode SKU <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="sku" 
                               name="sku" 
                               value="{{ old('sku', $product->sku) }}" 
                               class="w-full px-3.5 py-2.5 text-sm rounded-xl border @error('sku') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition font-mono uppercase"
                               required>
                        @error('sku')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category_id" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                            Kategori Produk <span class="text-rose-500">*</span>
                        </label>
                        <select id="category_id" 
                                name="category_id" 
                                class="w-full px-3.5 py-2.5 text-sm rounded-xl border @error('category_id') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition"
                                required>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Produk -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                            Nama Produk Cemilan / Minuman <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $product->name) }}" 
                               class="w-full px-3.5 py-2.5 text-sm rounded-xl border @error('name') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition"
                               required>
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Finansial & Kontrol Stok -->
            <div class="pt-2">
                <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-coksu-700 flex items-center">
                        <i class="fa-solid fa-coins mr-2 text-xs"></i> 2. Harga & Kontrol Stok
                    </h2>
                    <span class="text-[11px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                        <i class="fa-solid fa-circle-info mr-1"></i> Perubahan stok otomatis mencatat mutasi audit
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-4">
                    <!-- Harga Jual -->
                    <div>
                        <label for="price" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                            Harga Jual Satuan (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400 font-mono text-xs">
                                Rp
                            </span>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $product->price) }}" 
                                   min="0" 
                                   step="500" 
                                   class="w-full pl-10 pr-3.5 py-2.5 text-sm rounded-xl border @error('price') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition font-mono"
                                   required>
                        </div>
                        @error('price')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stok Fisik -->
                    <div>
                        <label for="stock" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                            Stok Fisik Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" 
                               id="stock" 
                               name="stock" 
                               value="{{ old('stock', $product->stock) }}" 
                               min="0" 
                               class="w-full px-3.5 py-2.5 text-sm rounded-xl border @error('stock') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition font-mono"
                               required>
                        @error('stock')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Batas Minimum Stok -->
                    <div>
                        <label for="minimum_stock" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                            Batas Minimum Stok <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" 
                               id="minimum_stock" 
                               name="minimum_stock" 
                               value="{{ old('minimum_stock', $product->minimum_stock) }}" 
                               min="0" 
                               class="w-full px-3.5 py-2.5 text-sm rounded-xl border @error('minimum_stock') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/50 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition font-mono"
                               required>
                        @error('minimum_stock')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Rekanan Supplier (Many-to-Many) -->
            <div class="pt-2">
                <h2 class="text-sm font-bold uppercase tracking-wider text-coksu-700 pb-2 border-b border-stone-100 flex items-center">
                    <i class="fa-solid fa-truck-ramp-box mr-2 text-xs"></i> 3. Rekanan Supplier (Many-to-Many)
                </h2>

                <p class="text-xs text-stone-500 mt-2 mb-3">Centang rekanan supplier yang menyuplai produk ini:</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach ($suppliers as $supplier)
                    <label class="flex items-start p-3 rounded-xl border border-stone-200 hover:border-coksu-300 bg-stone-50/40 hover:bg-[#FAF7F2] transition cursor-pointer select-none">
                        <input type="checkbox" 
                               name="suppliers[]" 
                               value="{{ $supplier->id }}" 
                               {{ in_array($supplier->id, old('suppliers', $selectedSuppliers)) ? 'checked' : '' }}
                               class="mt-1 w-4 h-4 text-coksu-500 rounded border-stone-300 focus:ring-coksu-400">
                        <div class="ml-3 text-xs">
                            <span class="font-bold text-stone-800 block">{{ $supplier->name }}</span>
                            <span class="text-stone-500 text-[11px] block mt-0.5">Kontak: {{ $supplier->contact_person ?? '-' }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('suppliers')
                    <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-stone-100 flex items-center justify-end space-x-3">
                <a href="{{ route('products.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-100 text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-coksu-500 hover:bg-coksu-600 text-white text-sm font-semibold shadow-md shadow-coksu-500/20 transition-all duration-150 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-check mr-2"></i> Perbarui Produk
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
