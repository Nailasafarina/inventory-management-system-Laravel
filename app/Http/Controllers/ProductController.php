<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menggunakan eager loading, pencarian, filter kategori, sorting whitelist,
     * serta pagination dengan query string URL tetap terjaga.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 1. Inisialisasi query dengan Eager Loading relasi Category dan Suppliers
        $query = Product::with(['category', 'suppliers']);

        // 2. Fitur Pencarian berdasarkan Nama Produk atau SKU
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // 3. Fitur Filter Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // 4. Fitur Filter Status Stok (Opsional: Rendah / Habis)
        if ($request->filled('stock_status')) {
            if ($request->input('stock_status') === 'low') {
                $query->whereColumn('stock', '<=', 'minimum_stock');
            } elseif ($request->input('stock_status') === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            }
        }

        // 5. Fitur Sorting dengan Whitelist (Keamanan terhadap SQL Injection)
        $allowedSortColumns = ['name', 'sku', 'price', 'stock', 'created_at'];
        $sortBy = in_array($request->input('sort_by'), $allowedSortColumns, true)
            ? $request->input('sort_by')
            : 'name';

        $sortDirection = strtolower($request->input('sort_direction')) === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortDirection);

        // 6. Pagination dengan query string terjaga (withQueryString)
        $products = $query->paginate(10)->withQueryString();

        // 7. Data kategori untuk opsi dropdown filter
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     * Validasi SKU unik, harga/stok tidak negatif, dan rekam audit mutasi stok awal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'   => ['required', 'exists:categories,id'],
            'sku'           => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name'          => ['required', 'string', 'max:150'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'suppliers'     => ['nullable', 'array'],
            'suppliers.*'   => ['exists:suppliers,id'],
        ], [
            'sku.unique'           => 'SKU produk ini sudah terdaftar di sistem.',
            'price.min'            => 'Harga produk tidak boleh bernilai negatif.',
            'stock.min'            => 'Stok produk tidak boleh bernilai negatif.',
            'minimum_stock.min'    => 'Batas minimum stok tidak boleh bernilai negatif.',
            'category_id.exists'   => 'Kategori yang dipilih tidak valid.',
            'suppliers.*.exists'   => 'Supplier yang dipilih tidak ditemukan.',
        ]);

        // Simpan data produk baru
        $product = Product::create([
            'category_id'   => $validated['category_id'],
            'sku'           => strtoupper(trim($validated['sku'])),
            'name'          => trim($validated['name']),
            'price'         => $validated['price'],
            'stock'         => $validated['stock'],
            'minimum_stock' => $validated['minimum_stock'],
        ]);

        // Hubungkan ke supplier jika ada yang dipilih
        if (!empty($validated['suppliers'])) {
            $product->suppliers()->sync($validated['suppliers']);
        }

        // Catat riwayat audit stok awal ke stock_movements jika stok > 0
        if ($product->stock > 0) {
            $authUserId = auth()->id() ?: (User::where('role', 'admin')->value('id') ?: 1);
            StockMovement::create([
                'product_id'     => $product->id,
                'user_id'        => $authUserId,
                'type'           => 'IN',
                'quantity'       => $product->stock,
                'reference_note' => 'Penerimaan stok awal produk baru',
            ]);
        }

        // Flash message sukses dan redirect ke index
        return redirect()->route('products.index')
                         ->with('success', "Produk '{$product->name}' (SKU: {$product->sku}) berhasil ditambahkan ke inventori!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        $product->load(['category', 'suppliers', 'stockMovements.user']);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $selectedSuppliers = $product->suppliers->pluck('id')->toArray();

        return view('products.edit', compact('product', 'categories', 'suppliers', 'selectedSuppliers'));
    }

    /**
     * Update the specified resource in storage.
     * Validasi SKU unik mengabaikan ID saat ini, dan catat selisih mutasi stok otomatis.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'   => ['required', 'exists:categories,id'],
            'sku'           => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
            'name'          => ['required', 'string', 'max:150'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'suppliers'     => ['nullable', 'array'],
            'suppliers.*'   => ['exists:suppliers,id'],
        ], [
            'sku.unique'           => 'SKU produk ini sudah digunakan oleh produk lain.',
            'price.min'            => 'Harga produk tidak boleh bernilai negatif.',
            'stock.min'            => 'Stok produk tidak boleh bernilai negatif.',
            'minimum_stock.min'    => 'Batas minimum stok tidak boleh bernilai negatif.',
            'category_id.exists'   => 'Kategori yang dipilih tidak valid.',
            'suppliers.*.exists'   => 'Supplier yang dipilih tidak valid.',
        ]);

        $oldStock = (int) $product->stock;
        $newStock = (int) $validated['stock'];

        $product->update([
            'category_id'   => $validated['category_id'],
            'sku'           => strtoupper(trim($validated['sku'])),
            'name'          => trim($validated['name']),
            'price'         => $validated['price'],
            'stock'         => $newStock,
            'minimum_stock' => $validated['minimum_stock'],
        ]);

        // Sinkronisasi relasi Many-to-Many supplier
        if (isset($validated['suppliers'])) {
            $product->suppliers()->sync($validated['suppliers']);
        } else {
            $product->suppliers()->detach();
        }

        // Catat selisih stok jika ada perubahan jumlah stok fisik
        if ($newStock !== $oldStock) {
            $difference = $newStock - $oldStock;
            $authUserId = auth()->id() ?: (User::where('role', 'admin')->value('id') ?: 1);

            StockMovement::create([
                'product_id'     => $product->id,
                'user_id'        => $authUserId,
                'type'           => $difference > 0 ? 'IN' : 'OUT',
                'quantity'       => abs($difference),
                'reference_note' => 'Penyesuaian stok manual melalui pembaruan master produk',
            ]);
        }

        return redirect()->route('products.index')
                         ->with('success', "Data produk '{$product->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified resource from storage.
     * Menangani relasi supplier dan perlindungan relasi database mutasi stok.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $productName = $product->name;

        // Lepaskan relasi pivot many-to-many supplier terlebih dahulu
        $product->suppliers()->detach();

        try {
            $product->delete();

            return redirect()->route('products.index')
                             ->with('success', "Produk '{$productName}' berhasil dihapus dari sistem!");
        } catch (QueryException $e) {
            // Tangani ON DELETE RESTRICT jika produk masih direferensikan tabel audit stock_movements
            return redirect()->route('products.index')
                             ->with('error', "Produk '{$productName}' tidak dapat dihapus karena memiliki riwayat audit mutasi stok.");
        }
    }
}
