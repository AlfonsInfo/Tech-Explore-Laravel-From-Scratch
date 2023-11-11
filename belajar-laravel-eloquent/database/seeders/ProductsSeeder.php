<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Products();
        $product->id = "1";
        $product->name = "Product 1";
        $product->description= "Product 1";
        $product->category_id= "FOOD";
        $product->save();
    }
}
