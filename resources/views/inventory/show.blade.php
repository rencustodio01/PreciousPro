@extends('layouts.app')
@section('title', 'Inventory Details')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $inventory->product->product_name }}</h2>
        <small>Inventory #{{ $inventory->id }} · {{ $inventory->product->category->category_name }}</small>
    </div>
    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-3">
    {{-- Stock Summary --}}
    <div class="col-lg-4">
        <div class="form-card">
            <h5 class="mb-3">Stock Summary</h5>
            <table class="table">
                <tr>
                    <th style="width:130px">Product</th>
                    <td><strong>{{ $inventory->product->product_name }}</strong></td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>{{ $inventory->product->category->category_name }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @php $qty = $inventory->quantity_available; @endphp
                        <span class="badge {{ $qty < 10 ? 'badge-fail' : ($qty < 50 ? 'badge-pending' : 'badge-pass') }}">
                            {{ $qty < 10 ? 'Low Stock' : ($qty < 50 ? 'Medium' : 'Good') }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Qty Available</th>
                    <td>
                        <span style="font-size:1.5rem;font-weight:700;font-family:'Playfair Display',serif">
                            {{ number_format($qty) }}
                        </span> units
                    </td>
                </tr>
                <tr>
                    <th>Last Updated</th>
                    <td>{{ $inventory->last_updated->format('M d, Y H:i') }}</td>
                </tr>
            </table>
        </div>

        {{-- Add Transaction Form --}}
        <div class="form-card mt-3">
            <h5 class="mb-3">Add Stock Transaction</h5>
            @if($errors->any())
                <div class="alert-danger-custom mb-3">
                    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('inventory.transaction') }}">
                @csrf
                <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
                <div class="mb-3">
                    <label class="form-label">Transaction Type <span class="text-danger">*</span></label>
                    <select name="transaction_type" class="form-select @error('transaction_type') is-invalid @enderror" required>
                        <option value="">Select type…</option>
                        <option value="Stock In" {{ old('transaction_type') == 'Stock In' ? 'selected' : '' }}>📦 Stock In</option>
                        <option value="Stock Out" {{ old('transaction_type') == 'Stock Out' ? 'selected' : '' }}>📤 Stock Out</option>
                    </select>
                    @error('transaction_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" required min="1">
                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Transaction Date <span class="text-danger">*</span></label>
                    <input type="date" name="transaction_date" class="form-control @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" required>
                    @error('transaction_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-gold w-100">
                    <i class="bi bi-arrow-left-right me-1"></i> Submit Transaction
                </button>
            </form>
        </div>
    </div>

    {{-- Transaction History --}}
    <div class="col-lg-8">
        <div class="data-card">
            <div class="data-card-header">
                <h5>Transaction History ({{ $transactions->total() }} total)</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>Processed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $txn)
                        <tr>
                            <td>{{ $txn->id }}</td>
                            <td>
                                <span class="badge {{ $txn->transaction_type === 'Stock In' ? 'badge-pass' : 'badge-fail' }}">
                                    {{ $txn->transaction_type === 'Stock In' ? '📦' : '📤' }} {{ $txn->transaction_type }}
                                </span>
                            </td>
                            <td>
                                <strong class="{{ $txn->transaction_type === 'Stock In' ? 'text-success' : 'text-danger' }}">
                                    {{ $txn->transaction_type === 'Stock In' ? '+' : '-' }}{{ number_format($txn->quantity) }}
                                </strong>
                            </td>
                            <td>{{ $txn->transaction_date->format('M d, Y H:i') }}</td>
                            <td>{{ $txn->processor->full_name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-arrow-left-right" style="font-size:2rem;display:block;margin-bottom:8px;opacity:0.3"></i>
                                No transactions yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $transactions->links() }}</div>
        </div>
    </div>
</div>
@endsection