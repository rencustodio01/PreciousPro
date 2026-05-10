@extends('layouts.app')
@section('title', 'System Log Details')

@section('content')
<div class="page-header">
    <div>
        <h2>System Log Details</h2>
        <small>Log entry #{{ $systemLog->id }}</small>
    </div>
    <a href="{{ route('systemlogs.index') }}" class="btn btn-gold"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h5>Log Information</h5>
    </div>
    <div class="p-4">
        <div class="row g-3">
            <div class="col-md-6"><strong>User:</strong> {{ $systemLog->user_email ?? $systemLog->user_name ?? '—' }}</div>
            <div class="col-md-6"><strong>Role:</strong> {{ $systemLog->user_role ?? $systemLog->role_name ?? '—' }}</div>
            <div class="col-md-6"><strong>Action:</strong> {{ $systemLog->action }}</div>
            <div class="col-md-6"><strong>Model:</strong> {{ $systemLog->model ?? '—' }}</div>
            <div class="col-md-12"><strong>Description:</strong> {{ $systemLog->description ?? $systemLog->route_name ?? '—' }}</div>
            <div class="col-md-6"><strong>IP Address:</strong> {{ $systemLog->ip_address ?? '—' }}</div>
            <div class="col-md-6"><strong>Created:</strong> {{ optional($systemLog->created_at)->format('Y-m-d H:i:s') }}</div>
        </div>

        @if(!empty($systemLog->payload))
        <hr>
        <pre class="mb-0" style="white-space:pre-wrap;">{{ $systemLog->payload }}</pre>
        @endif
    </div>
</div>
@endsection