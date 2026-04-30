@extends('layouts.app')
@section('title', 'Inspection Details')

@section('content')
<div class="page-header">
    <div><h2>Inspection #{{ $qualityInspection->id }}</h2><small>{{ $qualityInspection->production->product->product_name }}</small></div>
    <a href="{{ route('quality.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <table class="table">
                <tr><th style="width:150px">Product</th><td><strong>{{ $qualityInspection->production->product->product_name }}</strong></td></tr>
                <tr><th>Production Run</th><td>Run #{{ $qualityInspection->production_id }}</td></tr>
                <tr><th>Inspector</th><td>{{ $qualityInspection->inspector->full_name }}</td></tr>
                <tr><th>Inspection Date</th><td>{{ $qualityInspection->inspection_date->format('M d, Y') }}</td></tr>
                <tr><th>Result</th><td><span class="badge {{ $qualityInspection->result === 'Pass' ? 'badge-pass' : 'badge-fail' }}">{{ $qualityInspection->result }}</span></td></tr>
                <tr><th>Remarks</th><td>{{ $qualityInspection->remarks ?? '—' }}</td></tr>
            </table>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('quality.edit', $qualityInspection) }}" class="btn btn-gold"><i class="bi bi-pencil me-1"></i> Edit</a>
                <a href="{{ route('quality.index') }}" class="btn btn-outline-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection