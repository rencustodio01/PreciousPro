@extends('layouts.app')
@section('title', 'Quality Control')

@section('content')
<div class="page-header">
    <div><h2>Quality Inspections</h2><small>Record and track QC inspection results</small></div>
    <a href="{{ route('quality.create') }}" class="btn btn-gold"><i class="bi bi-plus-lg me-1"></i> New Inspection</a>
</div>

<form class="row g-2 mb-4" method="GET">
    <div class="col-md-3">
        <select name="result" class="form-select">
            <option value="">All Results</option>
            <option value="Pass" {{ request('result')=='Pass'?'selected':'' }}>Pass</option>
            <option value="Fail" {{ request('result')=='Fail'?'selected':'' }}>Fail</option>
        </select>
    </div>
    <div class="col-md-2"><button type="submit" class="btn btn-gold w-100">Filter</button></div>
</form>

<div class="data-card">
    <div class="data-card-header"><h5>Inspection Records ({{ $inspections->total() }} total)</h5></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Product</th><th>Inspector</th><th>Date</th><th>Result</th><th>Remarks</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($inspections as $ins)
                <tr>
                    <td>{{ $ins->id }}</td>
                    <td><strong>{{ $ins->production->product->product_name }}</strong><div class="text-muted" style="font-size:0.75rem">Run #{{ $ins->production_id }}</div></td>
                    <td>{{ $ins->inspector->full_name }}</td>
                    <td>{{ $ins->inspection_date->format('M d, Y') }}</td>
                    <td><span class="badge {{ $ins->result === 'Pass' ? 'badge-pass' : 'badge-fail' }}">{{ $ins->result }}</span></td>
                    <td><span style="font-size:0.82rem">{{ Str::limit($ins->remarks, 40) }}</span></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('quality.show', $ins) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('quality.edit', $ins) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('quality.destroy', $ins) }}" onsubmit="return confirm('Delete this inspection record?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted">No inspection records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $inspections->links() }}</div>
</div>
@endsection
