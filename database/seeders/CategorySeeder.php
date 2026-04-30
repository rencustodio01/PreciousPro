<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Ring', 'Necklace', 'Bracelet', 'Earring', 'Pendant', 'Brooch'];

        foreach ($categories as $name) {
            Category::firstOrCreate(['category_name' => $name]);
        }
    }
}
