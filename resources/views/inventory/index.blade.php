@extends('layouts.app')
@section('title', 'Inventory')

@section('content')
<div class="page-header">
    <div><h2>Inventory</h2><small>Monitor stock levels across all products</small></div>
    @if(request('low_stock'))
        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">Show All</a>
    @else
        <a href="{{ route('inventory.index', ['low_stock' => 1]) }}" class="btn btn-outline-warning"><i class="bi bi-exclamation-triangle me-1"></i> Low Stock Only</a>
    @endif
</div>

<div class="data-card">
    <div class="data-card-header"><h5>Stock Levels ({{ $inventories->total() }} items)</h5></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Product</th><th>Category</th><th>Qty Available</th><th>Last Updated</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($inventories as $inv)
                <tr>
                    <td>{{ $inv->id }}</td>
                    <td><strong>{{ $inv->product->product_name }}</strong></td>
                    <td>{{ $inv->product->category->category_name }}</td>
                    <td>
                        @if($inv->quantity_available < 10)
                            <span class="badge badge-fail"><i class="bi bi-exclamation-triangle me-1"></i>{{ $inv->quantity_available }}</span>
                        @elseif($inv->quantity_available < 50)
                            <span class="badge badge-pending">{{ $inv->quantity_available }}</span>
                        @else
                            <span class="badge badge-pass">{{ $inv->quantity_available }}</span>
                        @endif
                    </td>
                    <td>{{ $inv->last_updated->format('M d, Y H:i') }}</td>
                    <td><a href="{{ route('inventory.show', $inv) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i> View & Transact</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No inventory records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $inventories->links() }}</div>
</div>
@endsection
