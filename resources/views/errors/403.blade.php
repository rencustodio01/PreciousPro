@extends('layouts.app')
@section('title', 'Access Denied')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center" style="min-height:60vh;text-align:center">
    <div style="font-size:4rem;margin-bottom:16px">🔒</div>
    <h2 style="font-family:'Playfair Display',serif;color:#1C1C2E">Access Denied</h2>
    <p class="text-muted mb-2">You don't have permission to view this page.</p>
    <p class="text-muted mb-4" style="font-size:0.85rem">Your role: <strong>{{ auth()->user()->role?->role_name }}</strong></p>
    <a href="{{ route('dashboard') }}" class="btn btn-gold">
        <i class="bi bi-house me-1"></i> Back to Dashboard
    </a>
</div>
@endsection