@extends('layouts.app')
@section('title', 'Edit Production Run')

@section('content')
<div class="page-header">
    <div><h2>Edit Production Run</h2><small>Run #{{ $production->id }}</small></div>
    <a href="{{ route('production.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="form-card">
            @if($errors->any())
                <div class="alert-danger-custom mb-4">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <form method="POST" action="{{ route('production.update', $production) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Product <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                            <option value="">Select product…</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ old('product_id', $production->product_id) == $p->id ? 'selected' : '' }}>{{ $p->product_name }}</option>
                            @endforeach
                        </select>
                        @error('product_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Production Date <span class="text-danger">*</span></label>
                        <input type="date" name="production_date" class="form-control @error('production_date') is-invalid @enderror" value="{{ old('production_date', $production->production_date->format('Y-m-d')) }}" required max="{{ now()->format('Y-m-d') }}">
                        @error('production_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Quantity Produced <span class="text-danger">*</span></label>
                        <input type="number" name="quantity_produced" class="form-control @error('quantity_produced') is-invalid @enderror" value="{{ old('quantity_produced', $production->quantity_produced) }}" required min="1">
                        @error('quantity_produced')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Pending" {{ old('status', $production->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Completed" {{ old('status', $production->status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                        <a href="{{ route('production.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-gold"><i class="bi bi-check-lg me-1"></i> Update Production Run</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection