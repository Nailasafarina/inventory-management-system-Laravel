@extends('layouts.app')

@section('title', 'Catat Mutasi Stok')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-2 text-xs text-stone-500 font-medium">
            <a href="{{ route('stocks.index') }}" class="hover:text-coksu-600 transition flex items-center">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Riwayat
            </a>
            <span>/</span>
            <span class="text-stone-800 font-semibold">Transaksi Mutasi</span>
        </div>
        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
            <i class="fa-solid fa-shield-halved mr-1"></i> Proteksi ACID & Lock Transaksi
        </span>
    </div>

    <div>
        <h1 class="text-2xl font-bold tracking-tight text-stone-800">Catat Mutasi Stok Baru</h1>
        <p class="text-sm text-stone-500 mt-1">Perbarui kuantitas fisik persediaan barang masuk atau keluar dengan jaminan integritas data.</p>
    </div>

    <!-- Form Card Minimalis -->
    <div class="bg-white rounded-2xl border border-[#EFE9DF] p-6 sm:p-8 shadow-soft">
        <form action="{{ route('stocks.store') }}" method="POST" id="stockForm" class="space-y-6">
            @csrf

            <!-- 1. Pemilihan Produk -->
            <div>
                <label for="product_id" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                    Pilih Produk Cemilan / Minuman <span class="text-rose-500">*</span>
                </label>
                <select id="product_id" 
                        name="product_id" 
                        onchange="updateStockPreview()" 
                        class="w-full px-3.5 py-2.5 text-sm rounded-xl border @error('product_id') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/40 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition"
                        required>
                    <option value="" data-stock="0" data-min="0">-- Pilih Produk yang Akan Dimutasi --</option>
                    @foreach ($products as $prod)
                        <option value="{{ $prod->id }}" 
                                data-stock="{{ $prod->stock }}" 
                                data-min="{{ $prod->minimum_stock }}" 
                                {{ old('product_id', request('product_id')) == $prod->id ? 'selected' : '' }}>
                            [{{ $prod->sku }}] {{ $prod->name }} &bull; (Stok Saat Ini: {{ $prod->stock }} unit)
                        </option>
                    @endforeach
                </select>
                @error('product_id')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Jenis Mutasi (Radio Cards IN / OUT) -->
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-2">
                    Jenis Mutasi Stok <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Opsi IN -->
                    <label class="flex items-start p-4 rounded-xl border-2 border-stone-200 hover:border-emerald-300 bg-stone-50/30 hover:bg-emerald-50/20 transition cursor-pointer select-none relative group has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/40">
                        <input type="radio" 
                               name="type" 
                               value="IN" 
                               {{ old('type', 'IN') === 'IN' ? 'checked' : '' }}
                               onchange="updateStockPreview()"
                               class="mt-1 w-4 h-4 text-emerald-600 border-stone-300 focus:ring-emerald-500">
                        <div class="ml-3">
                            <span class="font-bold text-sm text-stone-800 flex items-center">
                                <i class="fa-solid fa-arrow-down text-emerald-600 mr-2 text-xs"></i> Stok Masuk (IN)
                            </span>
                            <span class="text-xs text-stone-500 block mt-1 leading-normal">
                                Penerimaan barang supplier, produksi fresh batch dapur, atau retur stok.
                            </span>
                        </div>
                    </label>

                    <!-- Opsi OUT -->
                    <label class="flex items-start p-4 rounded-xl border-2 border-stone-200 hover:border-rose-300 bg-stone-50/30 hover:bg-rose-50/20 transition cursor-pointer select-none relative group has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/40">
                        <input type="radio" 
                               name="type" 
                               value="OUT" 
                               {{ old('type') === 'OUT' ? 'checked' : '' }}
                               onchange="updateStockPreview()"
                               class="mt-1 w-4 h-4 text-rose-600 border-stone-300 focus:ring-rose-500">
                        <div class="ml-3">
                            <span class="font-bold text-sm text-stone-800 flex items-center">
                                <i class="fa-solid fa-arrow-up text-rose-600 mr-2 text-xs"></i> Stok Keluar (OUT)
                            </span>
                            <span class="text-xs text-stone-500 block mt-1 leading-normal">
                                Penjualan reguler, catering, pemakaian display, atau barang rusak.
                            </span>
                        </div>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- 3. Kuantitas Mutasi -->
            <div>
                <label for="quantity" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                    Kuantitas Mutasi (Jumlah Unit) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" 
                           id="quantity" 
                           name="quantity" 
                           value="{{ old('quantity', 1) }}" 
                           min="1" 
                           oninput="updateStockPreview()" 
                           placeholder="Contoh: 10" 
                           class="w-full px-3.5 py-2.5 text-sm rounded-xl border @error('quantity') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/40 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition font-mono"
                           required>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-stone-400 text-xs font-medium">
                        unit
                    </span>
                </div>
                @error('quantity')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. Dynamic Live Projection Preview Card -->
            <div id="previewCard" class="p-4 rounded-xl border border-stone-200/80 bg-stone-50/60 text-xs space-y-2">
                <div class="flex items-center justify-between text-stone-600">
                    <span class="font-medium">Stok Fisik Saat Ini:</span>
                    <span id="currentStockDisplay" class="font-mono font-bold text-stone-800">0 unit</span>
                </div>
                <div class="flex items-center justify-between text-stone-600">
                    <span class="font-medium">Estimasi Stok Setelah Mutasi:</span>
                    <span id="projectedStockDisplay" class="font-mono font-bold text-coksu-700">0 unit</span>
                </div>
                <div id="negativeStockWarning" class="hidden pt-1 text-rose-600 font-semibold flex items-center">
                    <i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Peringatan: Stok keluar melebihi stok yang ada di gudang!
                </div>
            </div>

            <!-- 5. Keterangan / Referensi -->
            <div>
                <label for="reference_note" class="block text-xs font-semibold uppercase tracking-wider text-stone-600 mb-1.5">
                    Catatan Referensi Transaksi
                </label>
                <input type="text" 
                       id="reference_note" 
                       name="reference_note" 
                       value="{{ old('reference_note') }}" 
                       placeholder="Contoh: Pesanan Snack Box Seminar, Restock CV Sentosa, dll." 
                       class="w-full px-3.5 py-2.5 text-sm rounded-xl border @error('reference_note') border-rose-300 bg-rose-50/30 @else border-stone-200 bg-stone-50/40 @enderror focus:bg-white focus:outline-none focus:ring-2 focus:ring-coksu-400 focus:border-transparent transition">
                @error('reference_note')
                    <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @else
                    <p class="mt-1 text-[11px] text-stone-400">Tercatat di audit log untuk pertanggungjawaban audit kasir/gudang.</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-stone-100 flex items-center justify-end space-x-3">
                <a href="{{ route('stocks.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-100 text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-coksu-500 hover:bg-coksu-600 text-white text-sm font-semibold shadow-md shadow-coksu-500/20 transition-all duration-150 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Transaksi Mutasi
                </button>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
    function updateStockPreview() {
        const productSelect = document.getElementById('product_id');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const currentStock = parseInt(selectedOption.getAttribute('data-stock') || '0', 10);

        const typeInput = document.querySelector('input[name="type"]:checked');
        const type = typeInput ? typeInput.value : 'IN';

        const qtyInput = document.getElementById('quantity');
        const qty = parseInt(qtyInput.value || '0', 10);

        const currentDisplay = document.getElementById('currentStockDisplay');
        const projectedDisplay = document.getElementById('projectedStockDisplay');
        const warning = document.getElementById('negativeStockWarning');

        currentDisplay.textContent = currentStock + ' unit';

        let projected = currentStock;
        if (type === 'IN') {
            projected = currentStock + qty;
            warning.classList.add('hidden');
        } else {
            projected = currentStock - qty;
            if (projected < 0) {
                warning.classList.remove('hidden');
            } else {
                warning.classList.add('hidden');
            }
        }

        projectedDisplay.textContent = projected + ' unit';
    }

    document.addEventListener('DOMContentLoaded', updateStockPreview);
</script>
@endpush
@endsection
