<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Product 1',
                'detail' => 'Detail of Product 1',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);

            unset($product);
        }
    }
}
