@extends('layouts.app')
@section('title', 'Product Details')

@section('content')
<div class="page-header">
    <div><h2>{{ $product->product_name }}</h2><small>{{ $product->category->category_name }}</small></div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="form-card">
            <h5 class="mb-3">Product Details</h5>
            <table class="table">
                <tr>
                    <th style="width:130px">ID</th>
                    <td>{{ $product->id }}</td>
                </tr>
                <tr>
                    <th>Product Name</th>
                    <td><strong>{{ $product->product_name }}</strong></td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>{{ $product->category->category_name }}</td>
                </tr>
                <tr>
                    <th>Base Price</th>
                    <td>₱{{ number_format($product->base_price, 2) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @php
                            $bc = match($product->status) {
                                'Active' => 'badge-pass',
                                'In Production' => 'badge-pending',
                                'Discontinued' => 'badge-fail',
                                default => 'badge-gold'
                            };
                        @endphp
                        <span class="badge {{ $bc }}">{{ $product->status }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $product->description ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Created</th>
                    <td>{{ $product->created_at->format('M d, Y') }}</td>
                </tr>
            </table>

            {{-- Inventory --}}
            <div class="mt-3 p-3" style="background:#FFF8E1;border-radius:10px;border:1px solid rgba(184,150,12,0.3)">
                <div class="d-flex align-items-center justify-content-between">
                    <span style="font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.6px">Stock Available</span>
                    @if($product->inventory)
                        @php $qty = $product->inventory->quantity_available; @endphp
                        <span class="badge {{ $qty < 10 ? 'badge-fail' : ($qty < 50 ? 'badge-pending' : 'badge-pass') }}" style="font-size:1rem;padding:6px 14px">
                            {{ number_format($qty) }} units
                        </span>
                    @else
                        <span class="badge badge-fail">No inventory record</span>
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('products.edit', $product) }}" class="btn btn-gold">
                    <i class="bi bi-pencil me-1"></i> Edit Product
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back to List</a>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        {{-- Production Runs --}}
        <div class="data-card mb-3">
            <div class="data-card-header"><h5>Production Runs</h5></div>
            <table class="table table-hover">
                <thead>
                    <tr><th>#</th><th>Date</th><th>Qty Produced</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($product->productions as $prod)
                    <tr>
                        <td>{{ $prod->id }}</td>
                        <td>{{ $prod->production_date->format('M d, Y') }}</td>
                        <td>{{ number_format($prod->quantity_produced) }}</td>
                        <td>
                            <span class="badge {{ $prod->status === 'Completed' ? 'badge-pass' : 'badge-pending' }}">
                                {{ $prod->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('production.show', $prod) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-3 text-muted">No production runs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Quality Inspections --}}
        <div class="data-card">
            <div class="data-card-header"><h5>Quality Inspections</h5></div>
            <table class="table table-hover">
                <thead>
                    <tr><th>Run #</th><th>Date</th><th>Result</th><th>Remarks</th></tr>
                </thead>
                <tbody>
                    @php
                        $inspections = $product->productions->flatMap(fn($p) => $p->qualityInspections);
                    @endphp
                    @forelse($inspections as $ins)
                    <tr>
                        <td>Run #{{ $ins->production_id }}</td>
                        <td>{{ $ins->inspection_date->format('M d, Y') }}</td>
                        <td>
                            <span class="badge {{ $ins->result === 'Pass' ? 'badge-pass' : 'badge-fail' }}">
                                {{ $ins->result }}
                            </span>
                        </td>
                        <td>{{ Str::limit($ins->remarks, 40) ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No inspections yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection