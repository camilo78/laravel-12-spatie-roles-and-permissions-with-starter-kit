<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\View\View;
use Livewire\Component;

class ProductIndex extends Component
{
    public function render(): View
    {
        return view('livewire.products.product-index', [
            'products' => Product::latest()->get(),
        ]);
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();

        session()->flash('success', 'Product deleted successfully.');
    }
}
