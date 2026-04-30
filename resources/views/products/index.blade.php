@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="page-header">
    <div><h2>Jewelry Products</h2><small>Manage all product catalog items</small></div>
    <a href="{{ route('products.create') }}" class="btn btn-gold"><i class="bi bi-plus-lg me-1"></i> Add Product</a>
</div>

<form class="row g-2 mb-4" method="GET">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search product name…" value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
        <select name="category" class="form-select">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            @foreach(['Active','In Production','Discontinued'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2"><button type="submit" class="btn btn-gold w-100">Filter</button></div>
</form>

<div class="data-card">
    <div class="data-card-header">
        <h5>Products <span class="text-muted" style="font-size:0.8rem">({{ $products->total() }} total)</span></h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>#</th><th>Product Name</th><th>Category</th><th>Base Price</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        <strong>{{ $product->product_name }}</strong>
                        <div class="text-muted" style="font-size:0.78rem">{{ Str::limit($product->description, 50) }}</div>
                    </td>
                    <td>{{ $product->category->category_name }}</td>
                    <td>₱{{ number_format($product->base_price, 2) }}</td>
                    <td>
                        @php $bc = match($product->status) { 'Active'=>'badge-pass','In Production'=>'badge-pending','Discontinued'=>'badge-fail',default=>'badge-gold' }; @endphp
                        <span class="badge {{ $bc }}">{{ $product->status }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Mark as Discontinued?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-archive"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-gem" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.3"></i>
                        No products found. <a href="{{ route('products.create') }}">Add your first product</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $products->links() }}</div>
</div>
@endsection
