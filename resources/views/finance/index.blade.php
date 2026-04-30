@extends('layouts.app')
@section('title', 'Finance Records')

@section('content')
<div class="page-header">
    <div><h2>Finance Records</h2><small>Track production costs (Material, Labor, Overhead)</small></div>
    <a href="{{ route('finance.create') }}" class="btn btn-gold"><i class="bi bi-plus-lg me-1"></i> Add Record</a>
</div>

<div class="row g-3 mb-4">
    @foreach(['Material','Labor','Overhead'] as $ct)
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-{{ $ct === 'Material' ? 'box' : ($ct === 'Labor' ? 'person-workspace' : 'building') }}"></i></div>
            <div class="kpi-value">₱{{ number_format($costSummary[$ct] ?? 0, 0) }}</div>
            <div class="kpi-label">{{ $ct }} Cost (Month)</div>
        </div>
    </div>
    @endforeach
</div>

<form class="row g-2 mb-4" method="GET">
    <div class="col-md-3">
        <select name="cost_type" class="form-select">
            <option value="">All Cost Types</option>
            @foreach(['Material','Labor','Overhead'] as $ct)
                <option value="{{ $ct }}" {{ request('cost_type') == $ct ? 'selected' : '' }}>{{ $ct }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-gold w-100">Filter</button>
    </div>
</form>

<div class="data-card">
    <div class="data-card-header"><h5>Finance Records ({{ $records->total() }} total)</h5></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Production / Product</th>
                    <th>Cost Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Recorded By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                <tr>
                    <td>{{ $rec->id }}</td>
                    <td>
                        <strong>{{ $rec->production->product->product_name }}</strong>
                        <div class="text-muted" style="font-size:0.75rem">Run #{{ $rec->production_id }}</div>
                    </td>
                    <td><span class="badge badge-gold">{{ $rec->cost_type }}</span></td>
                    <td><strong>₱{{ number_format($rec->amount, 2) }}</strong></td>
                    <td>{{ $rec->record_date->format('M d, Y') }}</td>
                    <td>{{ $rec->recorder->full_name }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('finance.show', $rec) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('finance.edit', $rec) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('finance.destroy', $rec) }}" onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">No finance records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $records->links() }}</div>
</div>
@endsection