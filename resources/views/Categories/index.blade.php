@extends('layouts.app')
@section('title', 'Categories')

@section('content')
<div class="page-header">
    <div><h2>Categories</h2><small>Manage jewelry product categories</small></div>
    <a href="{{ route('categories.create') }}" class="btn btn-gold"><i class="bi bi-plus-lg me-1"></i> Add Category</a>
</div>

<div class="data-card">
    <div class="data-card-header"><h5>All Categories ({{ $categories->count() }} total)</h5></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Category Name</th><th>Products</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->id }}</td>
                    <td><strong>{{ $cat->category_name }}</strong></td>
                    <td><span class="badge badge-gold">{{ $cat->products_count }}</span></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-5 text-muted">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection