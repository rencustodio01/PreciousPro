@extends('layouts.app')
@section('title', 'New Inspection')

@section('content')
<div class="page-header">
    <div><h2>New Quality Inspection</h2><small>Record a QC inspection result</small></div>
    <a href="{{ route('quality.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
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
            <form method="POST" action="{{ route('quality.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Production Run <span class="text-danger">*</span></label>
                        <select name="production_id" class="form-select @error('production_id') is-invalid @enderror" required>
                            <option value="">Select production run…</option>
                            @foreach($productions as $prod)
                                <option value="{{ $prod->id }}" {{ old('production_id') == $prod->id ? 'selected' : '' }}>
                                    Run #{{ $prod->id }} — {{ $prod->product->product_name }} ({{ $prod->production_date->format('M d, Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('production_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Inspection Date <span class="text-danger">*</span></label>
                        <input type="date" name="inspection_date" class="form-control @error('inspection_date') is-invalid @enderror" value="{{ old('inspection_date', now()->format('Y-m-d')) }}" required max="{{ now()->format('Y-m-d') }}">
                        @error('inspection_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Result <span class="text-danger">*</span></label>
                        <select name="result" class="form-select @error('result') is-invalid @enderror" required>
                            <option value="">Select result…</option>
                            <option value="Pass" {{ old('result') == 'Pass' ? 'selected' : '' }}>✅ Pass</option>
                            <option value="Fail" {{ old('result') == 'Fail' ? 'selected' : '' }}>❌ Fail</option>
                        </select>
                        @error('result')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3" maxlength="150" placeholder="Optional notes about the inspection…">{{ old('remarks') }}</textarea>
                        @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                        <a href="{{ route('quality.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-gold"><i class="bi bi-check-lg me-1"></i> Save Inspection</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection