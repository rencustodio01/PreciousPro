@extends('layouts.app')
@section('title', 'Production Run Details')

@section('content')
<div class="page-header">
    <div><h2>Production Run #{{ $production->id }}</h2><small>{{ $production->product->product_name }}</small></div>
    <a href="{{ route('production.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-3">
    <div class="col-lg-5">
        <div class="form-card">
            <h5 class="mb-3">Run Details</h5>
            <table class="table">
                <tr><th style="width:140px">Product</th><td><strong>{{ $production->product->product_name }}</strong></td></tr>
                <tr><th>Date</th><td>{{ $production->production_date->format('M d, Y') }}</td></tr>
                <tr><th>Qty Produced</th><td>{{ number_format($production->quantity_produced) }}</td></tr>
                <tr><th>Status</th><td><span class="badge {{ $production->status === 'Completed' ? 'badge-pass' : 'badge-pending' }}">{{ $production->status }}</span></td></tr>
            </table>
            <a href="{{ route('production.edit', $production) }}" class="btn btn-gold btn-sm"><i class="bi bi-pencil me-1"></i> Edit</a>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="data-card mb-3">
            <div class="data-card-header"><h5>Quality Inspections</h5></div>
            <table class="table table-hover">
                <thead><tr><th>Date</th><th>Inspector</th><th>Result</th><th>Remarks</th></tr></thead>
                <tbody>
                    @forelse($production->qualityInspections as $ins)
                    <tr>
                        <td>{{ $ins->inspection_date->format('M d, Y') }}</td>
                        <td>{{ $ins->inspector->full_name }}</td>
                        <td><span class="badge {{ $ins->result === 'Pass' ? 'badge-pass' : 'badge-fail' }}">{{ $ins->result }}</span></td>
                        <td>{{ $ins->remarks ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No inspections yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="data-card">
            <div class="data-card-header"><h5>Finance Records</h5></div>
            <table class="table table-hover">
                <thead><tr><th>Cost Type</th><th>Amount</th><th>Date</th><th>Recorded By</th></tr></thead>
                <tbody>
                    @forelse($production->financeRecords as $rec)
                    <tr>
                        <td><span class="badge badge-gold">{{ $rec->cost_type }}</span></td>
                        <td>₱{{ number_format($rec->amount, 2) }}</td>
                        <td>{{ $rec->record_date->format('M d, Y') }}</td>
                        <td>{{ $rec->recorder->full_name }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No finance records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection