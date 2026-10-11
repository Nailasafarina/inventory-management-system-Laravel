<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan ringkasan metrik bisnis, status persediaan, dan audit mutasi stok.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Total Jenis Master Produk
        $totalProducts = Product::count();

        // 2. Total Valuasi Aset Inventori Fisik: SUM(stock * price)
        $totalValuation = (float) (Product::selectRaw('SUM(stock * price) as valuation')->value('valuation') ?: 0);

        // 3. Total Kuantitas Fisik Keseluruhan Produk
        $totalStockUnits = (int) Product::sum('stock');

        // 4. Jumlah Produk Kritis / Stok Rendah (stock <= minimum_stock)
        $criticalStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        // 5. Total Master Kategori & Supplier Rekanan
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        // 6. Akumulasi Mutasi Stok Masuk vs Keluar
        $totalMovementsIn = (int) StockMovement::where('type', 'IN')->sum('quantity');
        $totalMovementsOut = (int) StockMovement::where('type', 'OUT')->sum('quantity');

        // 7. Daftar Produk yang Memerlukan Restock Segera (Stok Kritis)
        $criticalProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // 8. Riwayat 5 Transaksi Mutasi Stok Terbaru (Audit Trail)
        $recentMovements = StockMovement::with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalValuation',
            'totalStockUnits',
            'criticalStockCount',
            'totalCategories',
            'totalSuppliers',
            'totalMovementsIn',
            'totalMovementsOut',
            'criticalProducts',
            'recentMovements'
        ));
    }
}
