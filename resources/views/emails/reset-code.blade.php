<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DM Sans', Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
        .container { max-width:480px; margin:40px auto; background:white; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08); }
        .header { background:#1C1C2E; padding:32px; text-align:center; }
        .header h1 { color:#D4AF37; font-size:1.5rem; margin:0; }
        .header p { color:#8B8FA8; font-size:0.8rem; margin:4px 0 0; letter-spacing:2px; text-transform:uppercase; }
        .body { padding:40px 32px; text-align:center; }
        .body p { color:#555; line-height:1.6; margin-bottom:24px; }
        .code-box { background:#FFF8E1; border:2px dashed #D4AF37; border-radius:12px; padding:24px; margin:24px 0; }
        .code { font-size:3rem; font-weight:700; color:#1C1C2E; letter-spacing:12px; font-family:monospace; }
        .expiry { color:#999; font-size:0.8rem; margin-top:8px; }
        .footer { background:#f9f9f9; padding:20px; text-align:center; color:#aaa; font-size:0.75rem; border-top:1px solid #eee; }
        .warning { background:#FFF3CD; border:1px solid #FFD700; border-radius:8px; padding:12px; font-size:0.82rem; color:#856404; margin-top:16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💎 PreciousPro</h1>
            <p>Quality Management System</p>
        </div>
        <div class="body">
            <p>You requested a password reset. Use the verification code below to proceed.</p>

            <div class="code-box">
                <div class="code">{{ $code }}</div>
                <div class="expiry">⏱ This code expires in <strong>10 minutes</strong></div>
            </div>

            <p>Enter this code on the verification page to reset your password.</p>

            <div class="warning">
                ⚠️ If you did not request this, please ignore this email. Your password will not be changed.
            </div>
        </div>
        <div class="footer">
            © {{ date('Y') }} PreciousPro · This is an automated message, please do not reply.
        </div>
    </div>
</body>
</html>