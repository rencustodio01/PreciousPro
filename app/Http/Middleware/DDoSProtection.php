<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DDoSProtection
{
    protected int $maxRequests = 100;
    protected int $decaySeconds = 60;
    protected int $maxLoginRequests = 10;
    protected int $blockSeconds = 30;
    protected int $loginBlockSeconds = 60;  

    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $blockedKey      = "blocked_ip_{$ip}";
        $blockedUntilKey = "blocked_ip_until_{$ip}";

      
        if (Cache::has($blockedKey)) {
            $blockedUntil     = Cache::get($blockedUntilKey, now()->timestamp);
            $remainingSeconds = max(0, $blockedUntil - now()->timestamp);

            Log::channel('daily')->warning('Blocked IP Attempted Access', [
                'ip'                => $ip,
                'url'               => $request->fullUrl(),
                'remaining_seconds' => $remainingSeconds,
                'timestamp'         => now()->toDateTimeString(),
            ]);

            return response()->view('errors.429', [
                'remainingSeconds' => $remainingSeconds,
            ], 429);
        }

        // ── Login page protection ──────────────────────────────────
        if ($request->is('login')) {
            $loginKey   = "login_hits_{$ip}";
            $loginCount = (int) Cache::get($loginKey, 0);

            if ($loginCount >= $this->maxLoginRequests) {
                $blockUntil = now()->addSeconds($this->loginBlockSeconds);  

                Cache::put($blockedKey,      true,                   $blockUntil);
                Cache::put($blockedUntilKey, $blockUntil->timestamp, $blockUntil);
                Cache::forget($loginKey);

                $remainingSeconds = $this->loginBlockSeconds;  

                Log::channel('daily')->error('IP Blocked - Login Flood Detected', [
                    'ip'            => $ip,
                    'attempts'      => $loginCount,
                    'blocked_until' => $blockUntil->toDateTimeString(),
                    'timestamp'     => now()->toDateTimeString(),
                ]);

                return response()->view('errors.429', [
                    'remainingSeconds' => $remainingSeconds,
                ], 429);
            }

            Cache::put($loginKey, $loginCount + 1, now()->addMinutes(10));
        }

        // ── Global rate limit ──────────────────────────────────────
        $requestKey   = "req_count_{$ip}";
        $requestCount = (int) Cache::get($requestKey, 0);

        if ($requestCount >= $this->maxRequests) {
            $blockUntil = now()->addSeconds($this->blockSeconds);  

            Cache::put($blockedKey,      true,                   $blockUntil);
            Cache::put($blockedUntilKey, $blockUntil->timestamp, $blockUntil);
            Cache::forget($requestKey);

            $remainingSeconds = $this->blockSeconds;

            Log::channel('daily')->error('IP Blocked - Request Flood Detected', [
                'ip'            => $ip,
                'requests'      => $requestCount,
                'blocked_until' => $blockUntil->toDateTimeString(),
                'timestamp'     => now()->toDateTimeString(),
            ]);

            return response()->view('errors.429', [
                'remainingSeconds' => $remainingSeconds,
            ], 429);
        }

        Cache::put($requestKey, $requestCount + 1, now()->addSeconds($this->decaySeconds));

        return $next($request);
    }
}