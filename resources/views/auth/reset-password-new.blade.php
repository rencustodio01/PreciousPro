<x-guest-layout>

    @if($errors->any())
        <div class="alert-error">
            <i class="bi bi-exclamation-circle me-1"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div style="text-align:center; margin-bottom:24px;">
        <div style="font-size:2rem; margin-bottom:8px;">🔐</div>
        <p style="color:#8B8FA8; font-size:0.88rem; line-height:1.6;">
            Create a new password for<br>
            <strong style="color:#D4AF37;">{{ session('reset_email') }}</strong>
        </p>
    </div>

    <form method="POST" action="{{ route('password.reset.new') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">New Password</label>
            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="••••••••"
                required>
            @error('password')
                <span class="error-text">{{ $message }}</span>
            @enderror
            <small style="color:#555; font-size:0.75rem; margin-top:6px; display:block;">
                Min 8 characters, uppercase, lowercase, number, and symbol required.
            </small>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input
                type="password"
                name="password_confirmation"
                class="form-control"
                placeholder="••••••••"
                required>
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-lock me-1"></i> Reset Password
        </button>

        <div style="text-align:center; margin-top:20px;">
            <a href="{{ route('login') }}" class="forgot-link">
                <i class="bi bi-arrow-left me-1"></i> Back to Login
            </a>
        </div>

    </form>

</x-guest-layout>