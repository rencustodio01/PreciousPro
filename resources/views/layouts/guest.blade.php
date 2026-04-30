<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PreciousPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #12121F;
            padding: 10px;
            overflow-y: auto;
        }

        body::before {
            content: '';
            position: fixed; top: -200px; right: -200px;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(184,150,12,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed; bottom: -200px; left: -200px;
            width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(184,150,12,0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: #1C1C2E;
            border-radius: 16px;
            padding: 18px 28px 20px;   /* very compact */
            border: 1px solid rgba(184,150,12,0.25);
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }

        /* ── Brand — inline horizontal layout to save vertical space ── */
        .login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .brand-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #B8960C, #D4AF37);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            box-shadow: 0 6px 18px rgba(184,150,12,0.3);
        }
        .brand-text h3 {
            font-family: 'Playfair Display', serif;
            color: #D4AF37;
            font-size: 1.25rem;
            line-height: 1;
            margin-bottom: 2px;
        }
        .brand-text p {
            color: #8B8FA8;
            font-size: 0.62rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 0;
        }

        /* ── Form ── */
        .form-label {
            color: #8B8FA8;
            font-size: 0.68rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
            display: block;
        }
        .form-control {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            color: white;
            padding: 8px 12px;
            font-size: 0.84rem;
            width: 100%;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .form-control:focus {
            outline: none;
            border-color: #D4AF37;
            background: rgba(212,175,55,0.08);
            box-shadow: 0 0 0 3px rgba(212,175,55,0.12);
            color: white;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.2); }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.6); cursor: pointer;
        }

        .form-group { margin-bottom: 9px; }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .remember-label {
            display: flex; align-items: center; gap: 7px;
            color: #8B8FA8; font-size: 0.78rem; cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 14px; height: 14px;
            accent-color: #D4AF37; cursor: pointer;
        }
        .forgot-link {
            color: #D4AF37; font-size: 0.78rem;
            text-decoration: none; transition: color 0.2s;
        }
        .forgot-link:hover { color: #B8960C; }

        .btn-login {
            width: 100%;
            padding: 9px;
            background: linear-gradient(135deg, #B8960C, #D4AF37);
            border: none; border-radius: 9px;
            color: white; font-size: 0.83rem; font-weight: 600;
            letter-spacing: 1px; text-transform: uppercase;
            cursor: pointer; transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
            box-shadow: 0 4px 14px rgba(184,150,12,0.3);
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #D4AF37, #B8960C);
            transform: translateY(-1px);
        }

        .error-text {
            color: #F1948A; font-size: 0.70rem;
            margin-top: 2px; display: block;
        }
        .alert-error {
            background: rgba(231,76,60,0.15);
            border: 1px solid rgba(231,76,60,0.3);
            border-radius: 9px; padding: 8px 12px;
            color: #F1948A; font-size: 0.78rem; margin-bottom: 10px;
        }
        .alert-success {
            background: rgba(46,204,113,0.15);
            border: 1px solid rgba(46,204,113,0.3);
            border-radius: 9px; padding: 8px 12px;
            color: #82E0AA; font-size: 0.78rem; margin-bottom: 10px;
        }

        .divider { border-color: rgba(255,255,255,0.07); margin: 14px 0; }

        .login-footer {
            text-align: center; margin-top: 10px;
            color: #8B8FA8; font-size: 0.68rem;
        }

        @media (max-width: 480px) {
            .login-card { padding: 16px 16px; }
            .form-row-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            {{-- Brand: horizontal to save space --}}
            <div class="login-brand">
                <div class="brand-icon">💎</div>
                <div class="brand-text">
                    <h3>PreciousPro</h3>
                    <p>Quality Management System</p>
                </div>
            </div>
            {{ $slot }}
        </div>
        <div class="login-footer">
            © {{ date('Y') }} PreciousPro · All rights reserved
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>