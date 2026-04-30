<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PreciousPro – @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold: #0f0cb8; --gold-light: #D4AF37; --gold-pale: #FFF8E1;
            --charcoal: #1C1C2E; --sidebar-bg: #12121F; --sidebar-width: 260px;
            --text-muted-custom: rgb(8, 104, 91); --border: rgba(184,150,12,0.18);
        }
        body { font-family: 'DM Sans', sans-serif; background: #F8F7F4; color: var(--charcoal); margin: 0; }
        h1,h2,h3 { font-family: 'Playfair Display', serif; }
        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-width); height: 100vh;
            background: var(--sidebar-bg); z-index: 1000; display: flex;
            flex-direction: column; border-right: 1px solid rgba(255,255,255,0.05);
        }
        .sidebar-brand { padding: 24px 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.07); }
        .sidebar-brand h4 { color: var(--gold-light); font-family: 'Playfair Display',serif; font-size: 1.3rem; margin: 0; }
        .sidebar-brand small { color: var(--text-muted-custom); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.5px; }
        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }
        .nav-label { color: var(--text-muted-custom); font-size: 0.65rem; text-transform: uppercase; letter-spacing: 2px; padding: 12px 10px 6px; display: block; }
        .nav-link-item {
            display: flex; align-items: center; gap: 10px; padding: 10px 14px;
            color: rgba(191, 186, 212, 0.92); border-radius: 8px; text-decoration: none;
            font-size: 0.88rem; transition: all 0.2s; margin-bottom: 2px;
        }
        .nav-link-item:hover, .nav-link-item.active { background: rgba(184,150,12,0.15); color: var(--gold-light); }
        .nav-link-item i { font-size: 1rem; width: 18px; }
        .sidebar-footer { padding: 16px; border-top: 1px solid rgba(255,255,255,0.07); }
        .user-pill { display: flex; align-items: center; gap: 10px; padding: 10px; background: rgba(255,255,255,0.05); border-radius: 10px; }
        .user-avatar { width: 36px; height: 36px; background: var(--gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.85rem; }
        .user-info small { color: var(--text-muted-custom); font-size: 0.7rem; }
        .user-info span { color: white; font-size: 0.82rem; display: block; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; padding: 28px 32px; }
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 1px solid var(--border); }
        .page-header h2 { margin: 0; font-size: 1.6rem; }
        .page-header small { color: var(--text-muted-custom); font-size: 0.8rem; }
        .kpi-card { background: white; border-radius: 14px; padding: 22px; border: 1px solid rgba(0,0,0,0.06); position: relative; overflow: hidden; }
        .kpi-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--gold-light); }
        .kpi-icon { width: 46px; height: 46px; border-radius: 12px; background: var(--gold-pale); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: var(--gold); margin-bottom: 14px; }
        .kpi-value { font-size: 2rem; font-weight: 700; font-family: 'Playfair Display',serif; color: var(--charcoal); line-height: 1; }
        .kpi-label { font-size: 0.78rem; color: var(--text-muted-custom); text-transform: uppercase; letter-spacing: 0.8px; margin-top: 4px; }
        .data-card { background: white; border-radius: 14px; border: 1px solid rgba(0,0,0,0.06); overflow: hidden; }
        .data-card-header { padding: 18px 22px; border-bottom: 1px solid rgba(0,0,0,0.06); display: flex; align-items: center; justify-content: space-between; }
        .data-card-header h5 { margin: 0; font-size: 1rem; font-weight: 600; }
        .table { margin: 0; }
        .table thead th { background: #FAFAFA; color: var(--text-muted-custom); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; border-bottom: 1px solid rgba(0,0,0,0.06); padding: 14px 18px; }
        .table tbody td { padding: 14px 18px; vertical-align: middle; font-size: 0.88rem; border-color: rgba(0,0,0,0.04); }
        .table tbody tr:hover { background: rgba(184,150,12,0.04); }
        .badge-gold { background: var(--gold-pale); color: var(--gold); border: 1px solid var(--gold); }
        .badge-pass { background: #EAFAF1; color: #1E8449; border: 1px solid #82E0AA; }
        .badge-fail { background: #FDEDEC; color: #C0392B; border: 1px solid #F1948A; }
        .badge-pending { background: #EBF5FB; color: #1A5276; border: 1px solid #7FB3D3; }
        .badge { font-size: 0.72rem; padding: 4px 10px; border-radius: 20px; font-weight: 500; }
        .form-card { background: white; border-radius: 14px; border: 1px solid rgba(0,0,0,0.06); padding: 32px; }
        .form-control, .form-select { border: 1px solid rgba(0,0,0,0.12); border-radius: 8px; font-size: 0.88rem; padding: 10px 14px; }
        .form-control:focus, .form-select:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(184,150,12,0.12); }
        .form-label { font-size: 0.8rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px; }
        .btn-gold { background: var(--gold-light); color: white; border: none; border-radius: 8px; padding: 10px 20px; font-size: 0.86rem; font-weight: 500; transition: background 0.2s; }
        .btn-gold:hover { background: var(--gold); color: white; }
        .alert-success-custom { background: #EAFAF1; border: 1px solid #82E0AA; color: #1E8449; border-radius: 10px; padding: 14px 18px; }
        .alert-danger-custom { background: #FDEDEC; border: 1px solid #F1948A; color: #C0392B; border-radius: 10px; padding: 14px 18px; }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; padding: 16px; } }
    </style>
    @stack('styles')
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <h4>💎 PreciousPro</h4>
        <small>Quality Management</small>
    </div>
    <nav class="sidebar-nav">
        <span class="nav-label">Overview</span>
        <a href="{{ route('dashboard') }}" class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        @if(auth()->user()->hasAnyRole(['Admin', 'Production Manager']))
        <span class="nav-label">Catalog</span>
        <a href="{{ route('categories.index') }}" class="nav-link-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Categories
        </a>
        <a href="{{ route('products.index') }}" class="nav-link-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="bi bi-gem"></i> Products
        </a>
        <span class="nav-label">Manufacturing</span>
        <a href="{{ route('production.index') }}" class="nav-link-item {{ request()->routeIs('production.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Production
        </a>
        @endif
        @if(auth()->user()->hasAnyRole(['Admin', 'QC Officer']))
        <a href="{{ route('quality.index') }}" class="nav-link-item {{ request()->routeIs('quality.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i> Quality Control
        </a>
        @endif
        @if(auth()->user()->hasAnyRole(['Admin', 'Inventory Officer']))
        <span class="nav-label">Warehouse</span>
        <a href="{{ route('inventory.index') }}" class="nav-link-item {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Inventory
        </a>
        @endif
        @if(auth()->user()->hasAnyRole(['Admin', 'Finance Officer']))
        <span class="nav-label">Finance</span>
        <a href="{{ route('finance.index') }}" class="nav-link-item {{ request()->routeIs('finance.*') ? 'active' : '' }}">
            <i class="bi bi-currency-dollar"></i> Finance Records
        </a>
        @endif
        @if(auth()->user()->hasRole('Admin'))
        <span class="nav-label">Administration</span>
        <a href="{{ route('users.index') }}" class="nav-link-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
        <a href="{{ route('roles.index') }}" class="nav-link-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock"></i> Roles
        </a>
        @endif
    </nav>
    <div class="sidebar-footer">
        <div class="user-pill">
            <div class="user-avatar">{{ substr(auth()->user()->full_name, 0, 1) }}</div>
            <div class="user-info">
                <span>{{ auth()->user()->full_name }}</span>
                <small>{{ auth()->user()->role?->role_name }}</small>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-secondary w-100" style="font-size:0.78rem;">
                <i class="bi bi-box-arrow-left"></i> Sign Out
            </button>
        </form>
    </div>
</aside>
<main class="main-content">
    @if(session('success'))
        <div class="alert-success-custom mb-4"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-danger-custom mb-4"><i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}</div>
    @endif
    @yield('content')
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
