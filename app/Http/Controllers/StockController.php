<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    /**
     * Menampilkan riwayat transaksi mutasi stok masuk dan keluar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);

        // Filter tipe mutasi (IN / OUT)
        if ($request->filled('type') && in_array($request->input('type'), ['IN', 'OUT'], true)) {
            $query->where('type', $request->input('type'));
        }

        // Pencarian berdasarkan nama produk, SKU, atau catatan referensi
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('reference_note', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        $movements = $query->latest()->paginate(15)->withQueryString();

        return view('stocks.index', compact('movements'));
    }

    /**
     * Menampilkan form untuk mencatat transaksi mutasi stok baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $products = Product::orderBy('name')->get(['id', 'sku', 'name', 'stock', 'minimum_stock']);

        return view('stocks.create', compact('products'));
    }

    /**
     * Menyimpan transaksi mutasi stok dengan ACID Transaction dan Pessimistic Row Locking.
     * Mencegah race condition dan memastikan stok fisik tidak bernilai negatif.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'     => ['required', 'exists:products,id'],
            'type'           => ['required', 'in:IN,OUT'],
            'quantity'       => ['required', 'integer', 'min:1'],
            'reference_note' => ['nullable', 'string', 'max:255'],
        ], [
            'product_id.required' => 'Pilih produk yang akan dimutasi.',
            'product_id.exists'   => 'Produk yang dipilih tidak valid.',
            'type.required'       => 'Pilih jenis mutasi (Masuk atau Keluar).',
            'type.in'             => 'Jenis mutasi harus berupa IN (Masuk) atau OUT (Keluar).',
            'quantity.required'   => 'Kuantitas mutasi wajib diisi.',
            'quantity.min'        => 'Kuantitas mutasi minimal 1 unit.',
        ]);

        $productName = '';
        $typeLabel = $validated['type'] === 'IN' ? 'Stok Masuk (IN)' : 'Stok Keluar (OUT)';
        $qty = (int) $validated['quantity'];
        $newStock = 0;

        // Eksekusi ACID Transaction dengan Database Row Locking
        DB::transaction(function () use ($validated, $qty, &$productName, &$newStock) {
            // 1. Pessimistic Row Locking: Mengunci baris produk dari akses konkuren simultan
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $productName = $product->name;
            $type = $validated['type'];

            // 2. Validasi Semantik Integritas: Kuantitas keluar dilarang melebihi stok yang ada
            if ($type === 'OUT' && $product->stock < $qty) {
                throw ValidationException::withMessages([
                    'quantity' => "Kuantitas keluar ({$qty} unit) melebihi stok fisik saat ini ({$product->stock} unit). Transaksi dibatalkan untuk mencegah stok bernilai negatif.",
                ]);
            }

            // 3. Pembaruan kuantitas stok fisik produk
            if ($type === 'IN') {
                $product->stock += $qty;
            } else {
                $product->stock -= $qty;
            }
            $product->save();
            $newStock = $product->stock;

            // 4. Pencatatan Audit Trail ke Tabel stock_movements
            StockMovement::create([
                'product_id'     => $product->id,
                'user_id'        => Auth::id() ?: 1,
                'type'           => $type,
                'quantity'       => $qty,
                'reference_note' => $validated['reference_note'] ?: ($type === 'IN' ? 'Penerimaan barang masuk' : 'Pengeluaran/penjualan barang'),
            ]);
        });

        return redirect()->route('stocks.index')
                         ->with('success', "Transaksi {$typeLabel} untuk '{$productName}' sejumlah {$qty} unit berhasil dicatat! Sisa stok fisik saat ini: {$newStock} unit.");
    }
}
