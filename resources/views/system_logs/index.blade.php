@extends('layouts.app')
@section('title', 'System Logs')

@section('content')
<div class="page-header">
    <div>
        <h2>System Logs</h2>
        <small>Audit trail of authentication and critical user activities</small>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-xl-3">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-journal-text"></i></div>
            <div class="kpi-value">{{ $stats['total'] }}</div>
            <div class="kpi-label">Total Logs</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-3">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-calendar-day"></i></div>
            <div class="kpi-value">{{ $stats['today'] }}</div>
            <div class="kpi-label">Today</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-3">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-calendar-week"></i></div>
            <div class="kpi-value">{{ $stats['this_week'] }}</div>
            <div class="kpi-label">This Week</div>
        </div>
    </div>
    <div class="col-md-4 col-xl-3">
        <div class="kpi-card">
            <div class="kpi-icon"><i class="bi bi-people"></i></div>
            <div class="kpi-value">{{ $users->count() }}</div>
            <div class="kpi-label">Users With Logs</div>
        </div>
    </div>
</div>

<div class="data-card mb-3">
    <div class="data-card-header">
        <h5>Filters</h5>
    </div>
    <div class="p-3">
        <form method="GET">
            <div class="row g-2 mb-2">
                <div class="col-md-6 col-lg-4">
                    <label class="form-label mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search description, email, route">
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label mb-1">User</label>
                    <select name="user_id" class="form-select">
                        <option value="">All users</option>
                        @foreach($users as $id => $name)
                        <option value="{{ $id }}" @selected(request('user_id')==$id)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-lg-2">
                    <label class="form-label mb-1">Role</label>
                    <select name="role" class="form-select">
                        <option value="">All roles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role }}" @selected(request('role')===$role)>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-lg-3">
                    <label class="form-label mb-1">Action</label>
                    <select name="action" class="form-select">
                        <option value="">All actions</option>
                        @foreach($actions as $action)
                        <option value="{{ $action }}" @selected(request('action')===$action)>{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-md-6 col-lg-2">
                    <label class="form-label mb-1">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-6 col-lg-2">
                    <label class="form-label mb-1">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-6 col-lg-2">
                    <button type="submit" class="btn btn-gold w-100">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="data-card">
    <div class="data-card-header">
        <h5>System Logs ({{ $logs->total() }} total)</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>User Email</th>
                    <th>Role</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ optional($log->created_at)->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $log->{$emailColumn ?? 'user_email'} ?? $log->user_email ?? $log->user_name ?? '—' }}</td>
                    <td><span class="badge badge-pending">{{ $log->{$roleColumn ?? 'user_role'} ?? $log->user_role ?? $log->role_name ?? '—' }}</span></td>
                    <td>
                        <span class="badge badge-gold">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                    </td>
                    <td>
                        @php
                        $action = strtolower(str_replace([' ', '-'], '_', (string) ($log->action ?? '')));
                        $storedDescription = trim((string) ($log->description ?? ''));
                        $routeName = $log->route_name ?? data_get($log->meta, 'route') ?? null;
                        $urlPath = $log->url ? parse_url($log->url, PHP_URL_PATH) : null;
                        $modelSource = $log->model ?: data_get($log->meta, 'model') ?: '';
                        $moduleSource = $modelSource ?: $routeName ?: $urlPath ?: 'record';
                        $moduleSource = preg_replace('/\.(index|store|update|destroy|create|edit|show)$/', '', $moduleSource);
                        $moduleSource = preg_replace('/\/\d+$/', '', $moduleSource);
                        $moduleSource = str_replace(['-', '_', '/', '.'], ' ', $moduleSource);
                        $moduleSource = preg_replace('/\s+/', ' ', trim($moduleSource));
                        $module = $moduleSource !== '' ? ucwords($moduleSource) : 'Record';

                        $routeText = strtolower((string) $routeName);
                        $genericDescriptions = [
                        '',
                        'System change on record',
                        'Created new record in record',
                        'Updated record in record',
                        'Deleted record from record',
                        'Accessed record data',
                        ];

                        if ($action !== 'system_change' && $storedDescription !== '' && ! in_array($storedDescription, $genericDescriptions, true)) {
                        $description = $storedDescription;
                        } elseif ($action === 'login') {
                        $description = 'User logged in to the system';
                        } elseif ($action === 'logout') {
                        $description = 'User logged out from the system';
                        } elseif ($action === 'create') {
                        $description = "Created new record in {$module}";
                        } elseif ($action === 'update') {
                        $description = "Updated record in {$module}";
                        } elseif ($action === 'delete') {
                        $description = "Deleted record from {$module}";
                        } elseif ($action === 'view' || $action === 'access') {
                        $description = "Accessed {$module} data";
                        } elseif ($action === 'system_change') {
                        $method = strtoupper((string) ($log->method ?? data_get($log->meta, 'method') ?? ''));
                        $moduleText = strtolower($module);
                        $authRoute = str_contains($routeText, 'login') || str_contains($routeText, 'logout') || str_contains($routeText, 'auth') || str_contains($moduleText, 'login') || str_contains($moduleText, 'logout') || str_contains($moduleText, 'auth');

                        if ($authRoute) {
                        $description = match ($method) {
                        'POST' => 'Authentication request submitted',
                        'DELETE' => 'Authentication session closed',
                        default => 'Processed authentication request',
                        };
                        } else {
                        $description = match ($method) {
                        'POST' => "Created new record in {$module}",
                        'PUT', 'PATCH' => "Updated record in {$module}",
                        'DELETE' => "Deleted record from {$module}",
                        default => "Processed request in {$module}",
                        };
                        }
                        } else {
                        $description = ucfirst(str_replace('_', ' ', $action)) . " on {$module}";
                        }
                        @endphp
                        <div>{{ $description }}</div>
                        @if($routeName)
                        <small class="text-muted">{{ $routeName }}</small>
                        @endif
                    </td>
                    <td>{{ $log->ip_address ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">No logs found for the selected filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-top px-3 py-3 d-flex justify-content-center bg-light">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection