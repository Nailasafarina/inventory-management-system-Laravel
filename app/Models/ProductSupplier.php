<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'product_suppliers';

    /**
     * Tabel pivot tidak menggunakan auto-incrementing ID.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Menandakan apakah model memiliki kolom created_at dan updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'supplier_id',
        'supplier_price',
    ];

    /**
     * Konversi tipe data atribut (Attribute Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'supplier_price' => 'decimal:2',
    ];

    /**
     * Relasi ke model Product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Relasi ke model Supplier.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
