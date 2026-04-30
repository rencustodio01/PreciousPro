<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->when(request('search'), fn($q, $s) => $q->where('product_name', 'like', "%$s%"))
            ->when(request('category'), fn($q, $c) => $q->where('category_id', $c))
            ->when(request('status'), fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15)->withQueryString();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        // Auto-create inventory record for this product
        $product->inventory()->create(['quantity_available' => 0]);
        return redirect()->route('products.index')
            ->with('success', "Product '{$product->product_name}' created successfully.");
    }

    public function show(Product $product)
    {
        $product->load(['category', 'productions.qualityInspections', 'inventory']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->productions()->where('status', 'Pending')->exists()) {
            return back()->with('error', 'Cannot delete product with pending production runs.');
        }
        $product->update(['status' => 'Discontinued']);
        return redirect()->route('products.index')->with('success', 'Product marked as Discontinued.');
    }
}
