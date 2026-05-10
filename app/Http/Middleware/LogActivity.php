<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\SystemLogService;

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

        if (
            Auth::check()
            && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])
            && $response->getStatusCode() < 400
        ) {
            $user = Auth::user();
            $inputs = collect($request->except($this->sensitiveFields))
                ->map(fn($v) => is_string($v) ? substr($v, 0, 100) : $v)
                ->toArray();

            // keep existing file logging for compatibility
            Log::channel('daily')->info('User Activity', [
                'user_id'   => $user?->id,
                'user'      => $user?->full_name,
                'role'      => $user?->role?->role_name,
                'method'    => $request->method(),
                'url'       => $request->fullUrl(),
                'ip'        => $request->ip(),
                'user_agent' => $request->userAgent(),
                'inputs'    => $inputs,
                'timestamp' => now()->toDateTimeString(),
            ]);

            // Persist non-model system changes. Model CRUD is captured centrally via observer.
            SystemLogService::log([
                'action' => 'system_change',
                'model' => null,
                'meta' => [
                    'method' => $request->method(),
                    'inputs' => $inputs,
                    'url' => $request->fullUrl(),
                    'route' => optional($request->route())->getName(),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}
