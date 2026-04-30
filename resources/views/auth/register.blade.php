<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Full Name --}}
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control"
                value="{{ old('full_name') }}" required autofocus>
            @error('full_name')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control"
                value="{{ old('email') }}" required autocomplete="username">
            @error('email')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        {{-- Password + Confirm side by side --}}
        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-password-wrap">
                    <input type="password" name="password" id="reg-password"
                        class="form-control" required autocomplete="new-password">
                    <button type="button" class="toggle-password"
                        onclick="togglePassword('reg-password','eye-reg-password')" tabindex="-1">
                        <i class="bi bi-eye" id="eye-reg-password"></i>
                    </button>
                </div>
                @error('password')<span class="error-text">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="input-password-wrap">
                    <input type="password" name="password_confirmation" id="reg-confirm"
                        class="form-control" required autocomplete="new-password">
                    <button type="button" class="toggle-password"
                        onclick="togglePassword('reg-confirm','eye-reg-confirm')" tabindex="-1">
                        <i class="bi bi-eye" id="eye-reg-confirm"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Contact + Birthday side by side --}}
        <div class="form-row-2">
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control"
                    value="{{ old('contact_number') }}" required>
                @error('contact_number')<span class="error-text">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Birthday</label>
                <input type="date" name="birthday" class="form-control"
                    value="{{ old('birthday') }}" required>
                @error('birthday')<span class="error-text">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Birthplace --}}
        <div class="form-group">
            <label class="form-label">Birthplace</label>
            <input type="text" name="birthplace" class="form-control"
                value="{{ old('birthplace') }}" required>
            @error('birthplace')<span class="error-text">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-person-plus me-1"></i> Create Account
        </button>

        <div style="text-align:center; margin-top:14px;">
            <span style="color:#8B8FA8; font-size:0.78rem;">Already have an account?</span>
            <a href="{{ route('login') }}" class="forgot-link ms-1">
                <i class="bi bi-arrow-left me-1"></i> Sign In
            </a>
        </div>
    </form>

    <style>
        .input-password-wrap {
            position: relative;
        }
        .input-password-wrap .form-control {
            padding-right: 36px;
        }
        .toggle-password {
            position: absolute;
            right: 9px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #8B8FA8;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            font-size: 0.95rem;
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






























































































































































































































































































































































































































































































































































































