<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\View\View;
use Livewire\Component;

class ProductCreate extends Component
{
    public $name, $detail;

    public function render(): View
    {
        return view('livewire.products.product-create');
    }

    public function createProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'detail' => 'required',
        ]);

        Product::create([
            'name' => $this->name,
            'detail' => $this->detail,
        ]);

        return to_route('products.index')->with('success', 'Product created successfully.');
    }
}
