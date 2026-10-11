<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Kue & Bakery (Cake)',
                'description' => 'Kue basah, cookies renyah, dan pastry lezat khas Snack Corner',
            ],
            [
                'name' => 'Keripik & Snack Gurih (Chips)',
                'description' => 'Cemilan gurih, keripik kentang balado, dan aneka kerupuk renyah',
            ],
            [
                'name' => 'Minuman Manis & Segar (Drinks)',
                'description' => 'Matcha latte, milk tea boba, dan minuman dingin segar siap minum',
            ],
            [
                'name' => 'Cemilan Siap Saji (Ready to Eat)',
                'description' => 'Sosis bakar jumbo, risoles mayo, dan frozen snack lezat',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
