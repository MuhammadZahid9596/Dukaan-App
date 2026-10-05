<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', auth()->id())->latest()->get();
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Item add ho gaya!');
    }

    public function update(Request $request, Product $product)
    {
        abort_if($product->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'barcode' => 'nullable|string|max:255',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Item update ho gaya!');
    }

    public function destroy(Product $product)
    {
        abort_if($product->user_id !== auth()->id(), 403);

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Item delete ho gaya!');
    }
}