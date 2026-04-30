<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Too Many Requests – PreciousPro</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #12121F;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            color: white;
        }
        .box {
            text-align: center;
            padding: 48px;
            background: #1C1C2E;
            border-radius: 20px;
            border: 1px solid rgba(184,150,12,0.3);
            max-width: 480px;
            width: 90%;
        }
        .icon { font-size: 4rem; margin-bottom: 20px; }
        h2 {
            font-family: 'Playfair Display', serif;
            color: #D4AF37;
            font-size: 1.8rem;
            margin-bottom: 12px;
        }
        p { color: #8B8FA8; margin-bottom: 8px; line-height: 1.6; }
        .badge {
            display: inline-block;
            background: rgba(184,150,12,0.15);
            border: 1px solid rgba(184,150,12,0.3);
            color: #D4AF37;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-bottom: 24px;
        }
        .countdown {
            font-size: 2.5rem;
            font-weight: 700;
            color: #D4AF37;
            margin: 20px 0;
            font-family: 'Playfair Display', serif;
        }
        .countdown.done { color: #2ecc71; font-size: 1.2rem; }
        .progress-bar-wrap {
            background: rgba(255,255,255,0.05);
            border-radius: 99px;
            height: 6px;
            margin: 16px 0;
            overflow: hidden;
        }
        .progress-bar {
            height: 6px;
            background: linear-gradient(90deg, #B8960C, #D4AF37);
            border-radius: 99px;
            transition: width 1s linear;
        }
        .note { font-size: 0.78rem; color: #555; margin-top: 12px; }
        .redirecting { color: #2ecc71; font-size: 0.9rem; margin-top: 12px; display: none; }
    </style>
</head>
<body>
    <div class="box">
        <div class="icon">🛡️</div>
        <h2>Access Temporarily Blocked</h2>
        <span class="badge">Error 429 — Too Many Requests</span>
        <p>Our security system has detected unusual activity from your IP address.</p>
        <p>Please wait before trying again.</p>

        <div class="countdown" id="countdown">--</div>

        <div class="progress-bar-wrap">
            <div class="progress-bar" id="progressBar" style="width:100%"></div>
        </div>

        <p class="note">You will be automatically redirected to login when the timer ends.</p>
        <p class="note">If you believe this is a mistake, contact your system administrator.</p>
        <p class="redirecting" id="redirectMsg">✅ Redirecting to login page...</p>
    </div>

    <script>
       
        const TOTAL = {{ $remainingSeconds ?? 60 }};
        let seconds = TOTAL;

        const countdownEl = document.getElementById('countdown');
        const progressBar = document.getElementById('progressBar');
        const redirectMsg = document.getElementById('redirectMsg');

        function updateDisplay() {
            if (seconds <= 0) return;
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            countdownEl.textContent = m > 0 ? `${m}m ${s}s` : `${s}s`;
            progressBar.style.width = ((seconds / TOTAL) * 100) + '%';
        }

        updateDisplay();

        const timer = setInterval(() => {
            seconds--;
            updateDisplay();

            if (seconds <= 0) {
                clearInterval(timer);
                countdownEl.classList.add('done');
                countdownEl.textContent = 'Wait time complete!';
                progressBar.style.width = '0%';
                redirectMsg.style.display = 'block';

                // Redirect to login after 1.5 seconds
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            }
        }, 1000);
    </script>
</body>
</html>