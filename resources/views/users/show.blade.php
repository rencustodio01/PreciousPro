@extends('layouts.app')
@section('title', 'View User')

@section('content')
<div class="page-header">
    <div><h2>User Details</h2></div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="form-card">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width:56px;height:56px;border-radius:50%;background:#D4AF37;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.3rem">
                    {{ substr($user->full_name, 0, 1) }}
                </div>
                <div>
                    <h4 class="mb-0">{{ $user->full_name }}</h4>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>
            </div>

            {{-- Account Info --}}
            <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#D4AF37;padding-bottom:8px;border-bottom:1px solid rgba(212,175,55,0.2);margin-bottom:12px;">
                <i class="bi bi-person-circle me-1"></i> Account Information
            </div>
            <table class="table mb-4">
                <tr><th style="width:150px">ID</th><td>{{ $user->id }}</td></tr>
                <tr><th>Full Name</th><td>{{ $user->full_name }}</td></tr>
                <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                <tr><th>Role</th><td>
                    @if($user->role_id)
                        <span class="badge badge-pending">{{ $user->role?->role_name }}</span>
                    @else
                        <span style="color:#8B8FA8;font-style:italic;font-size:0.83rem;">No role assigned</span>
                    @endif
                </td></tr>
                <tr><th>Registered</th><td>{{ $user->created_at->format('M d, Y') }}</td></tr>
            </table>

            {{-- Personal Credentials --}}
            <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#D4AF37;padding-bottom:8px;border-bottom:1px solid rgba(212,175,55,0.2);margin-bottom:12px;">
                <i class="bi bi-shield-lock me-1"></i> Personal Credentials
            </div>
            <table class="table mb-4">
                <tr><th style="width:150px">Contact No.</th><td>{{ $user->contact_number ?? '—' }}</td></tr>
                <tr><th>Birthday</th><td>{{ $user->birthday ? $user->birthday->format('F d, Y') : '—' }}</td></tr>
                <tr><th>Birthplace</th><td>{{ $user->birthplace ?? '—' }}</td></tr>
            </table>

            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-gold"><i class="bi bi-pencil me-1"></i> Edit</a>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>
@endsection