<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = [
            [
                'name' => 'PT Dapur Cemilan Nusantara',
                'contact_person' => 'Ibu Rahmawati',
                'phone' => '021-5551234',
                'address' => 'Sentra Kuliner Blok C No. 12, Jakarta Selatan',
            ],
            [
                'name' => 'CV Sentosa Bakery & Pastry',
                'contact_person' => 'Pak Budi Hartono',
                'phone' => '021-5557890',
                'address' => 'Jl. Roti Kencana No. 8, Tangerang',
            ],
            [
                'name' => 'PT Sukses Minuman Segar',
                'contact_person' => 'Rian Saputra',
                'phone' => '021-5554321',
                'address' => 'Kawasan Industri Beverage Hijau Kav. 4, Bekasi',
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::firstOrCreate(['name' => $sup['name']], $sup);
        }
    }
}
