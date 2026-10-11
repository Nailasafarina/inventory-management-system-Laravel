<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil ID Kategori
        $catBakeryObj = Category::where('name', 'like', '%Bakery%')->first();
        $catChipsObj  = Category::where('name', 'like', '%Keripik%')->first();
        $catDrinksObj = Category::where('name', 'like', '%Minuman%')->first();
        $catRTEObj    = Category::where('name', 'like', '%Siap Saji%')->first();

        $catBakery = $catBakeryObj ? $catBakeryObj->id : 1;
        $catChips  = $catChipsObj  ? $catChipsObj->id  : 2;
        $catDrinks = $catDrinksObj ? $catDrinksObj->id : 3;
        $catRTE    = $catRTEObj    ? $catRTEObj->id    : 4;

        // Ambil ID Supplier
        $supDapurObj   = Supplier::where('name', 'like', '%Dapur Cemilan%')->first();
        $supSentosaObj = Supplier::where('name', 'like', '%Sentosa%')->first();
        $supSuksesObj  = Supplier::where('name', 'like', '%Sukses Minuman%')->first();

        $supDapur   = $supDapurObj   ? $supDapurObj->id   : 1;
        $supSentosa = $supSentosaObj ? $supSentosaObj->id : 2;
        $supSukses  = $supSuksesObj  ? $supSuksesObj->id  : 3;

        // Ambil User Admin untuk audit mutasi stok awal
        $admin = User::where('role', 'admin')->first() ?: User::first();

        $productsData = [
            [
                'category_id' => $catBakery,
                'sku' => 'SNK-CKI-001',
                'name' => 'Chocolate Chip Cookies (Box 6pcs)',
                'price' => 28000.00,
                'stock' => 25,
                'minimum_stock' => 5,
                'supplier_id' => $supSentosa,
                'supplier_price' => 22000.00,
            ],
            [
                'category_id' => $catBakery,
                'sku' => 'SNK-MCH-002',
                'name' => 'Peanut Filled Mochi (Isi 4pcs)',
                'price' => 18000.00,
                'stock' => 15,
                'minimum_stock' => 5,
                'supplier_id' => $supDapur,
                'supplier_price' => 13000.00,
            ],
            [
                'category_id' => $catDrinks,
                'sku' => 'DRK-MTC-003',
                'name' => 'Iced Matcha Latte 250ml',
                'price' => 16000.00,
                'stock' => 3, // Low stock
                'minimum_stock' => 5,
                'supplier_id' => $supSukses,
                'supplier_price' => 11500.00,
            ],
            [
                'category_id' => $catRTE,
                'sku' => 'SNK-RSL-004',
                'name' => 'Risoles Mayo Keju Beef (Isi 3pcs)',
                'price' => 21000.00,
                'stock' => 20,
                'minimum_stock' => 5,
                'supplier_id' => $supDapur,
                'supplier_price' => 16000.00,
            ],
            [
                'category_id' => $catRTE,
                'sku' => 'SNK-SSG-005',
                'name' => 'Jumbo Grilled Sausage',
                'price' => 15000.00,
                'stock' => 4, // Low stock
                'minimum_stock' => 5,
                'supplier_id' => $supDapur,
                'supplier_price' => 11000.00,
            ],
            [
                'category_id' => $catChips,
                'sku' => 'SNK-KPK-006',
                'name' => 'Keripik Kentang Balado Premium 100g',
                'price' => 17500.00,
                'stock' => 35,
                'minimum_stock' => 8,
                'supplier_id' => $supDapur,
                'supplier_price' => 13000.00,
            ],
            [
                'category_id' => $catBakery,
                'sku' => 'SNK-BWN-007',
                'name' => 'Fudgy Brownies Shiny Crust Mini',
                'price' => 24000.00,
                'stock' => 2, // Low stock
                'minimum_stock' => 4,
                'supplier_id' => $supSentosa,
                'supplier_price' => 18500.00,
            ],
            [
                'category_id' => $catDrinks,
                'sku' => 'DRK-MLK-008',
                'name' => 'Brown Sugar Boba Milk Tea 250ml',
                'price' => 18500.00,
                'stock' => 16,
                'minimum_stock' => 5,
                'supplier_id' => $supSukses,
                'supplier_price' => 13500.00,
            ],
        ];

        foreach ($productsData as $data) {
            $supplierId = $data['supplier_id'];
            $supplierPrice = $data['supplier_price'];
            unset($data['supplier_id'], $data['supplier_price']);

            $product = Product::firstOrCreate(['sku' => $data['sku']], $data);

            // Hubungkan relasi pivot Many-to-Many
            if ($supplierId) {
                $product->suppliers()->syncWithoutDetaching([
                    $supplierId => ['supplier_price' => $supplierPrice],
                ]);
            }

            // Catat pergerakan stok awal jika belum ada histori
            if ($admin && $product->stockMovements()->count() === 0 && $product->stock > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $admin->id,
                    'type' => 'IN',
                    'quantity' => $product->stock,
                    'reference_note' => 'Stok Awal InventPro P4',
                ]);
            }
        }
    }
}
