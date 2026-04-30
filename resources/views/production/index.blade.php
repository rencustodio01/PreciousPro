@extends('layouts.app')
@section('title', 'Production')

@section('content')
<div class="page-header">
    <div><h2>Production Runs</h2><small>Track jewelry manufacturing batches</small></div>
    <a href="{{ route('production.create') }}" class="btn btn-gold"><i class="bi bi-plus-lg me-1"></i> New Production Run</a>
</div>

<form class="row g-2 mb-4" method="GET">
    <div class="col-md-4">
        <select name="product" class="form-select">
            <option value="">All Products</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" {{ request('product') == $p->id ? 'selected' : '' }}>{{ $p->product_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="Pending" {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
            <option value="Completed" {{ request('status')=='Completed'?'selected':'' }}>Completed</option>
        </select>
    </div>
    <div class="col-md-2"><button type="submit" class="btn btn-gold w-100">Filter</button></div>
</form>

<div class="data-card">
    <div class="data-card-header"><h5>Production Runs ({{ $productions->total() }} total)</h5></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Product</th><th>Date</th><th>Qty Produced</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($productions as $prod)
                <tr>
                    <td>{{ $prod->id }}</td>
                    <td><strong>{{ $prod->product->product_name }}</strong></td>
                    <td>{{ $prod->production_date->format('M d, Y') }}</td>
                    <td>{{ number_format($prod->quantity_produced) }}</td>
                    <td><span class="badge {{ $prod->status === 'Completed' ? 'badge-pass' : 'badge-pending' }}">{{ $prod->status }}</span></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('production.show', $prod) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('production.edit', $prod) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            @if($prod->status !== 'Completed')
                            <form method="POST" action="{{ route('production.destroy', $prod) }}" onsubmit="return confirm('Delete this production run?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">No production runs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $productions->links() }}</div>
</div>
@endsection
