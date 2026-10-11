<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'price',
        'stock',
        'minimum_stock',
    ];

    /**
     * Konversi tipe data atribut (Attribute Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
    ];

    /**
     * Relasi Many-to-One: Setiap produk dimiliki oleh satu kategori.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Relasi Many-to-Many: Satu produk dapat dipasok oleh banyak supplier.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers', 'product_id', 'supplier_id')
                    ->withPivot('supplier_price');
    }

    /**
     * Relasi One-to-Many ke tabel pivot product_suppliers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productSuppliers()
    {
        return $this->hasMany(ProductSupplier::class, 'product_id', 'id');
    }

    /**
     * Relasi One-to-Many: Satu produk memiliki banyak riwayat mutasi stok.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'product_id', 'id');
    }
}
