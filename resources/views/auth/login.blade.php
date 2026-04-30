<x-guest-layout>

    @if(session('status'))
        <div class="alert-success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <i class="bi bi-exclamation-circle me-1"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email: no placeholder --}}
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control"
                value="{{ old('email') }}"
                required autofocus autocomplete="username">
            @error('email')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        {{-- Password with show/hide toggle --}}
        <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-password-wrap">
                <input type="password" name="password" id="password"
                    class="form-control" required autocomplete="current-password">
                <button type="button" class="toggle-password" onclick="togglePassword('password', 'eye-password')" tabindex="-1">
                    <i class="bi bi-eye" id="eye-password"></i>
                </button>
            </div>
            @error('password')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        {{-- Math CAPTCHA --}}
        @php
            if (!session()->has('captcha_a')) {
                session(['captcha_a' => rand(1, 9), 'captcha_b' => rand(1, 9)]);
            }
            $a = session('captcha_a');
            $b = session('captcha_b');
        @endphp
        <div class="form-group">
            <label class="form-label">
                <i class="bi bi-shield-check me-1"></i>
                Security Check: What is {{ $a }} + {{ $b }}?
            </label>
            <input type="number" name="captcha" class="form-control"
                placeholder="Enter the answer"
                required autocomplete="off">
            @error('captcha')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <div class="remember-row">
            <label class="remember-label">
                <input type="checkbox" name="remember"> Remember me
            </label>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
        </button>

        <div style="text-align:center; margin-top:14px;">
            <span style="color:#8B8FA8; font-size:0.78rem;">Don't have an account?</span>
            <a href="{{ route('register') }}" class="forgot-link ms-1">
                <i class="bi bi-person-plus me-1"></i> Register
            </a>
        </div>
    </form>

    <style>
        .input-password-wrap {
            position: relative;
        }
        .input-password-wrap .form-control {
            padding-right: 40px;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #8B8FA8;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            font-size: 1rem;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: #D4AF37; }
    </style>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>

</x-guest-layout>