@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="page-header">
    <div><h2>Edit User</h2><small>Update user #{{ $user->id }}</small></div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="form-card">
            @if($errors->any())
                <div class="alert-danger-custom mb-4">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf @method('PUT')
                <div class="row g-3">

                    {{-- Account Info --}}
                    <div class="col-12">
                        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#D4AF37;padding-bottom:8px;border-bottom:1px solid rgba(212,175,55,0.2);margin-bottom:4px;">
                            <i class="bi bi-person-circle me-1"></i> Account Information
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                            value="{{ old('full_name', $user->full_name) }}" required maxlength="100">
                        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required maxlength="100">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" minlength="8">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                            <option value="">Select role…</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Personal Credentials --}}
                    <div class="col-12 mt-2">
                        <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#D4AF37;padding-bottom:8px;border-bottom:1px solid rgba(212,175,55,0.2);margin-bottom:4px;">
                            <i class="bi bi-shield-lock me-1"></i> Personal Credentials
                            <span style="font-size:0.68rem;color:#8B8FA8;font-weight:400;text-transform:none;letter-spacing:0;margin-left:8px;">Used for password reset verification</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                            value="{{ old('contact_number', $user->contact_number) }}" maxlength="20" placeholder="09XX XXX XXXX">
                        @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Birthday</label>
                        <input type="date" name="birthday" class="form-control @error('birthday') is-invalid @enderror"
                            value="{{ old('birthday', $user->birthday?->format('Y-m-d')) }}" max="{{ now()->subDay()->format('Y-m-d') }}">
                        @error('birthday')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Birthplace</label>
                        <input type="text" name="birthplace" class="form-control @error('birthplace') is-invalid @enderror"
                            value="{{ old('birthplace', $user->birthplace) }}" maxlength="100" placeholder="City, Province">
                        @error('birthplace')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-gold"><i class="bi bi-check-lg me-1"></i> Update User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection