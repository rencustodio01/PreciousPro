@extends('layouts.app')
@section('title', 'Finance Record Details')

@section('content')
<div class="page-header">
    <div><h2>Finance Record #{{ $financeRecord->id }}</h2><small>{{ $financeRecord->production->product->product_name }}</small></div>
    <a href="{{ route('finance.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            <table class="table">
                <tr>
                    <th style="width:150px">Product</th>
                    <td><strong>{{ $financeRecord->production->product->product_name }}</strong></td>
                </tr>
                <tr>
                    <th>Production Run</th>
                    <td>Run #{{ $financeRecord->production_id }}</td>
                </tr>
                <tr>
                    <th>Cost Type</th>
                    <td><span class="badge badge-gold">{{ $financeRecord->cost_type }}</span></td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td><strong>₱{{ number_format($financeRecord->amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <th>Record Date</th>
                    <td>{{ $financeRecord->record_date->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <th>Recorded By</th>
                    <td>{{ $financeRecord->recorder->full_name }}</td>
                </tr>
                <tr>
                    <th>Created</th>
                    <td>{{ $financeRecord->created_at->format('M d, Y H:i') }}</td>
                </tr>
            </table>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('finance.edit', $financeRecord) }}" class="btn btn-gold">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <form method="POST" action="{{ route('finance.destroy', $financeRecord) }}" onsubmit="return confirm('Delete this record?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger"><i class="bi bi-trash me-1"></i> Delete</button>
                </form>
                <a href="{{ route('finance.index') }}" class="btn btn-outline-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection