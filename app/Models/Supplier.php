<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'address',
    ];

    /**
     * Relasi Many-to-Many: Satu supplier dapat memasok banyak produk.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_suppliers', 'supplier_id', 'product_id')
                    ->withPivot('supplier_price');
    }

    /**
     * Relasi One-to-Many ke tabel pivot product_suppliers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productSuppliers()
    {
        return $this->hasMany(ProductSupplier::class, 'supplier_id', 'id');
    }
}
