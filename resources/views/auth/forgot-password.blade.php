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

    <div style="text-align:center; margin-bottom:24px;">
        <div style="width:52px;height:52px;background:rgba(212,175,55,0.12);border:1px solid rgba(212,175,55,0.3);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin:0 auto 12px;">
            🔑
        </div>
        <p style="color:#8B8FA8; font-size:0.83rem; line-height:1.6; margin:0;">
            Enter your registered email address. We'll ask you to verify your
            identity using your personal details on the next step.
        </p>
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control"
                value="{{ old('email') }}"
                placeholder="your@email.com"
                required autofocus>
            @error('email')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-arrow-right me-1"></i> Continue
        </button>

        <div style="text-align:center; margin-top:18px;">
            <a href="{{ route('login') }}" class="forgot-link" style="font-size:0.82rem;">
                <i class="bi bi-arrow-left me-1"></i> Back to Login
            </a>
        </div>
    </form>

</x-guest-layout>