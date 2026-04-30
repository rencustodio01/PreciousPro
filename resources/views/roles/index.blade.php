@extends('layouts.app')
@section('title', 'Roles')

@section('content')
<div class="page-header">
    <div><h2>Roles</h2><small>Manage system roles and permissions</small></div>
    <a href="{{ route('roles.create') }}" class="btn btn-gold"><i class="bi bi-plus-lg me-1"></i> Add Role</a>
</div>

<div class="data-card">
    <div class="data-card-header"><h5>All Roles ({{ $roles->count() }} total)</h5></div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Role Name</th><th>Description</th><th>Users</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td><strong>{{ $role->role_name }}</strong></td>
                    <td>{{ $role->description ?? '—' }}</td>
                    <td><span class="badge badge-gold">{{ $role->users_count }}</span></td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('roles.destroy', $role) }}" onsubmit="return confirm('Delete this role?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">No roles found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection