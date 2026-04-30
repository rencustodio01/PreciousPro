<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogActivity
{
    protected array $sensitiveFields = [
        'password',
        'password_confirmation',
        'captcha',
        'token',
        'secret',
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $inputs = collect($request->except($this->sensitiveFields))
                ->map(fn($v) => is_string($v) ? substr($v, 0, 100) : $v)
                ->toArray();

            Log::channel('daily')->info('User Activity', [
                'user_id'   => auth()->id(),
                'user'      => auth()->user()->full_name,
                'role'      => auth()->user()->role?->role_name,
                'method'    => $request->method(),
                'url'       => $request->fullUrl(),
                'ip'        => $request->ip(),
                'inputs'    => $inputs,
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        return $response;
    }
}