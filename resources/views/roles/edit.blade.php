@extends('layouts.app')
@section('title', 'Add Role')

@section('content')
<div class="page-header">
    <div><h2>Add Role</h2></div>
    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="form-card">
            @if($errors->any())
                <div class="alert-danger-custom mb-4">
                    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Role Name <span class="text-danger">*</span></label>
                    <input type="text" name="role_name" class="form-control @error('role_name') is-invalid @enderror" value="{{ old('role_name') }}" required maxlength="50">
                    @error('role_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" maxlength="150">
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-gold">Save Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection