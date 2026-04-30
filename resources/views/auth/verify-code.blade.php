<x-guest-layout>

    @if(session('status'))
        <div style="background:rgba(46,204,113,0.1); border:1px solid rgba(46,204,113,0.3); border-radius:10px; padding:12px 16px; margin-bottom:20px; color:#2ECC71; font-size:0.83rem;">
            <i class="bi bi-check-circle me-1"></i> {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <i class="bi bi-exclamation-circle me-1"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Header --}}
    <div style="text-align:center; margin-bottom:24px;">
        <div style="width:52px;height:52px;background:rgba(212,175,55,0.12);border:1px solid rgba(212,175,55,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin:0 auto 12px;">
            🔐
        </div>
        <p style="color:#8B8FA8; font-size:0.83rem; line-height:1.6; margin:0;">
            To verify your identity, please enter the personal details
            you registered with.
        </p>
    </div>

    <form method="POST" action="{{ route('password.verify') }}">
        @csrf

        {{-- Full Name --}}
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control"
                value="{{ old('full_name') }}"
                placeholder="Enter your registered full name"
                required autofocus>
            @error('full_name')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        {{-- Birthday --}}
        <div class="form-group">
            <label class="form-label">Birthday</label>
            <input type="date" name="birthday" class="form-control"
                value="{{ old('birthday') }}"
                required max="{{ now()->subDay()->format('Y-m-d') }}">
            @error('birthday')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        {{-- Birthplace --}}
        <div class="form-group">
            <label class="form-label">Birthplace</label>
            <input type="text" name="birthplace" class="form-control"
                value="{{ old('birthplace') }}"
                placeholder="Enter your registered birthplace"
                required maxlength="100">
            @error('birthplace')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-shield-check me-1"></i> Verify Identity
        </button>

        <div style="text-align:center; margin-top:18px;">
            <a href="{{ route('password.request') }}" class="forgot-link" style="font-size:0.82rem;">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </form>

</x-guest-layout>