<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Awaiting Approval — PreciousPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            overflow: hidden; /* no scroll — everything fits */
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #12121F;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 16px;
        }

        body::before {
            content: '';
            position: fixed;
            top: -200px; right: -200px;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(184,150,12,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -200px; left: -200px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(184,150,12,0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .pending-wrapper {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
        }

        .pending-card {
            background: #1C1C2E;
            border-radius: 18px;
            padding: 28px 36px 24px; /* reduced padding */
            border: 1px solid rgba(184,150,12,0.25);
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
            text-align: center;
        }

        /* Smaller hourglass */
        .hourglass-icon {
            width: 60px;
            height: 60px;
            background: rgba(184,150,12,0.15);
            border: 2px solid rgba(184,150,12,0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto 14px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(184,150,12,0.3); }
            50%       { box-shadow: 0 0 0 10px rgba(184,150,12,0); }
        }

        .pending-title {
            font-family: 'Playfair Display', serif;
            color: #D4AF37;
            font-size: 1.5rem; /* reduced */
            margin-bottom: 8px;
        }

        .pending-subtitle {
            color: #8B8FA8;
            font-size: 0.82rem;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .user-info {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 10px 16px;
            margin-bottom: 16px;
        }

        .user-info .user-name {
            color: white;
            font-weight: 600;
            font-size: 0.88rem;
            margin-bottom: 2px;
        }

        .user-info .user-email {
            color: #8B8FA8;
            font-size: 0.78rem;
        }

        .steps {
            text-align: left;
            margin-bottom: 20px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 10px; /* reduced gap between steps */
        }

        .step:last-child { margin-bottom: 0; }

        .step-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .step-num.done {
            background: rgba(212,175,55,0.2);
            border: 1.5px solid #D4AF37;
            color: #D4AF37;
        }

        .step-num.pending {
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.2);
            color: #8B8FA8;
        }

        .step-text {
            color: #C5C8D8;
            font-size: 0.80rem; /* reduced */
            line-height: 1.45;
            padding-top: 3px;
        }

        .step-text strong { color: white; }

        .btn-signout {
            width: 100%;
            padding: 10px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            color: #8B8FA8;
            font-size: 0.84rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-signout:hover {
            background: rgba(231,76,60,0.12);
            border-color: rgba(231,76,60,0.3);
            color: #F1948A;
        }

        .pending-footer {
            text-align: center;
            margin-top: 12px;
            color: #8B8FA8;
            font-size: 0.70rem;
        }
    </style>
</head>
<body>
    <div class="pending-wrapper">
        <div class="pending-card">

            <div class="hourglass-icon">⏳</div>

            <h2 class="pending-title">Awaiting Approval</h2>
            <p class="pending-subtitle">
                Your account has been created successfully. You're
                currently waiting for an administrator to assign your role.
            </p>

            <div class="user-info">
                <div class="user-name">{{ auth()->user()->full_name }}</div>
                <div class="user-email">{{ auth()->user()->email }}</div>
            </div>

            <div class="steps">
                <div class="step">
                    <div class="step-num done">✓</div>
                    <div class="step-text">
                        <strong>Account Created</strong> — Your registration was successful.
                    </div>
                </div>
                <div class="step">
                    <div class="step-num pending">2</div>
                    <div class="step-text">
                        <strong>Admin Review</strong> — An administrator will assign your role in the system.
                    </div>
                </div>
                <div class="step">
                    <div class="step-num pending">3</div>
                    <div class="step-text">
                        <strong>Access Granted</strong> — Once your role is assigned, you can log in and start working.
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-signout">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </button>
            </form>

        </div>
        <div class="pending-footer">
            © {{ date('Y') }} PreciousPro · All rights reserved
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>