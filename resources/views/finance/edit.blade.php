@extends('layouts.app')
@section('title', 'Edit Finance Record')

@section('content')
<div class="page-header">
    <div><h2>Edit Finance Record</h2><small>Record #{{ $financeRecord->id }}</small></div>
    <a href="{{ route('finance.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
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
            <form method="POST" action="{{ route('finance.update', ['finance' => $financeRecord->id]) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Production Run <span class="text-danger">*</span></label>
                        <select name="production_id" class="form-select @error('production_id') is-invalid @enderror" required>
                            <option value="">Select production run…</option>
                            @foreach($productions as $prod)
                                <option value="{{ $prod->id }}" {{ old('production_id', $financeRecord->production_id) == $prod->id ? 'selected' : '' }}>
                                    Run #{{ $prod->id }} — {{ $prod->product->product_name }} ({{ $prod->production_date->format('M d, Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('production_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cost Type <span class="text-danger">*</span></label>
                        <select name="cost_type" class="form-select @error('cost_type') is-invalid @enderror" required>
                            @foreach(['Material','Labor','Overhead'] as $ct)
                                <option value="{{ $ct }}" {{ old('cost_type', $financeRecord->cost_type) == $ct ? 'selected' : '' }}>{{ $ct }}</option>
                            @endforeach
                        </select>
                        @error('cost_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Amount (₱) <span class="text-danger">*</span></label>
                        <input type="number" name="amount" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $financeRecord->amount) }}" required>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Record Date <span class="text-danger">*</span></label>
                        <input type="date" name="record_date" class="form-control @error('record_date') is-invalid @enderror" value="{{ old('record_date', $financeRecord->record_date->format('Y-m-d')) }}" required max="{{ now()->format('Y-m-d') }}">
                        @error('record_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                        <a href="{{ route('finance.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-gold"><i class="bi bi-check-lg me-1"></i> Update Record</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection