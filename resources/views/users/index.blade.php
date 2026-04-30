@extends('layouts.app')
@section('title', 'Users')

@section('content')
<div class="page-header">
    <div><h2>User Management</h2><small>Manage system users and their roles</small></div>
    <a href="{{ route('users.create') }}" class="btn btn-gold"><i class="bi bi-person-plus me-1"></i> Add User</a>
</div>

{{-- Pending Users Alert --}}
@php $pendingCount = $users->filter(fn($u) => !$u->role_id)->count(); @endphp
@if($pendingCount > 0)
<div style="background:rgba(212,175,55,0.1); border:1px solid rgba(212,175,55,0.3); border-radius:12px; padding:14px 20px; margin-bottom:20px; display:flex; align-items:center; gap:12px;">
    <i class="bi bi-exclamation-circle" style="color:#D4AF37; font-size:1.2rem;"></i>
    <span style="color:#D4AF37; font-size:0.88rem;">
        <strong>{{ $pendingCount }} user{{ $pendingCount > 1 ? 's' : '' }}</strong> registered without a role. Please assign their roles below.
    </span>
</div>
@endif

<div class="data-card">
    <div class="data-card-header"><h5>System Users ({{ $users->total() }} total)</h5></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:34px;height:34px;border-radius:50%;background:{{ $user->role_id ? '#D4AF37' : '#555' }};display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:0.82rem">
                                {{ substr($user->full_name, 0, 1) }}
                            </div>
                            <div>
                                <strong>{{ $user->full_name }}</strong>
                                @if($user->id === auth()->id())
                                    <span class="badge badge-gold ms-1">You</span>
                                @endif
                                @if(!$user->role_id)
                                    <span class="badge" style="background:#FEF3C7;color:#92400E;border:1px solid #FCD34D;margin-left:4px;font-size:0.68rem;">⏳ Pending</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role_id)
                            <span class="badge badge-pending">{{ $user->role?->role_name }}</span>
                        @else
                            <span style="color:#8B8FA8; font-size:0.82rem; font-style:italic;">No role assigned</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="{{ $user->role_id ? 'Edit' : 'Assign Role' }}">
                                <i class="bi bi-{{ $user->role_id ? 'pencil' : 'person-badge' }}"></i>
                                @if(!$user->role_id) <span style="font-size:0.75rem"> Assign</span> @endif
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $users->links() }}</div>
</div>
@endsection