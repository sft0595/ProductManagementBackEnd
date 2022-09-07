<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        Product::factory()
            ->count(20)
            ->create();

        foreach (Product::all() as $product) {
            $categories = Category::inRandomOrder()->whereRaw('categories._rgt - categories._lft = 1')->first()->id;
            $product->categories()->attach($categories);
        }
    }
}
