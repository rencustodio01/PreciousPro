<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => ['required', 'string', 'max:50', 'unique:categories,category_name'],
        ]);
        Category::create($request->only('category_name'));
        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => ['required', 'string', 'max:50', 'unique:categories,category_name,' . $category->id],
        ]);
        $category->update($request->only('category_name'));
        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Cannot delete category with existing products.');
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
