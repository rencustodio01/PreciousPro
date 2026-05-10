<?php

namespace App\Services;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;

class SystemLogService
{
    private static ?array $columns = null;

    public static function log(array $data): ?SystemLog
    {
        $request = request();
        $user = Auth::user();
        $columns = self::columns();

        $email = $data['user_email'] ?? $user?->email;
        $displayUser = $data['user_name'] ?? $email ?? $user?->full_name ?? 'System';
        $role = $data['user_role'] ?? $user?->role?->role_name;
        $action = self::normalizeAction($data['action'] ?? 'unknown');
        $model = $data['model'] ?? $data['model_name'] ?? null;
        $ipAddress = $data['ip_address'] ?? ($request?->ip() ?? Request::ip());
        $userAgent = $data['user_agent'] ?? $request?->userAgent();
        $meta = $data['meta'] ?? null;
        $routeName = $data['route_name'] ?? optional($request?->route())->getName();
        $method = $data['method'] ?? $request?->method();
        $url = $data['url'] ?? $request?->fullUrl();
        $description = self::buildDescription($action, $model, $routeName, $method, $url, $meta);

        $payload = [];

        if (in_array('user_id', $columns, true)) {
            $payload['user_id'] = $data['user_id'] ?? $user?->id;
        }

        if (in_array('user_email', $columns, true)) {
            $payload['user_email'] = $email;
        }

        if (in_array('user_name', $columns, true)) {
            $payload['user_name'] = $displayUser;
        }

        if (in_array('user_role', $columns, true)) {
            $payload['user_role'] = $role;
        }

        if (in_array('role_name', $columns, true)) {
            $payload['role_name'] = $role;
        }

        if (in_array('action', $columns, true)) {
            $payload['action'] = $action;
        }

        if (in_array('model', $columns, true)) {
            $payload['model'] = $model;
        }

        if (in_array('description', $columns, true)) {
            $payload['description'] = $description;
        }

        if (in_array('ip_address', $columns, true)) {
            $payload['ip_address'] = $ipAddress;
        }

        if (in_array('user_agent', $columns, true)) {
            $payload['user_agent'] = $userAgent;
        }

        if (in_array('meta', $columns, true)) {
            $payload['meta'] = $meta;
        }

        if (in_array('route_name', $columns, true)) {
            $payload['route_name'] = $routeName;
        }

        if (in_array('method', $columns, true)) {
            $payload['method'] = $method;
        }

        if (in_array('url', $columns, true)) {
            $payload['url'] = $url;
        }

        if (in_array('payload', $columns, true)) {
            $payload['payload'] = json_encode([
                'model' => $model,
                'description' => $description,
                'meta' => $meta,
                'user_agent' => $userAgent,
            ]);
        }

        try {
            return SystemLog::create($payload);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    private static function columns(): array
    {
        if (self::$columns !== null) {
            return self::$columns;
        }

        try {
            self::$columns = Schema::getColumnListing('system_logs');
        } catch (\Throwable $e) {
            self::$columns = [];
        }

        return self::$columns;
    }

    private static function buildDescription(string $action, ?string $model, ?string $routeName, ?string $method, ?string $url, $meta): string
    {
        $module = self::resolveModuleLabel($model, $routeName, $url, $meta);
        $action = self::normalizeAction($action);

        return match ($action) {
            'login' => 'User logged in to the system',
            'logout' => 'User logged out from the system',
            'create' => "Created new record in {$module}",
            'update' => "Updated record in {$module}",
            'delete' => "Deleted record from {$module}",
            'view', 'access' => "Accessed {$module} data",
            'system_change' => self::describeSystemChange($method, $module),
            default => ucfirst(str_replace('_', ' ', $action)) . " on {$module}",
        };
    }

    private static function describeSystemChange(?string $method, string $module): string
    {
        $moduleText = strtolower($module);

        if (str_contains($moduleText, 'login') || str_contains($moduleText, 'logout') || str_contains($moduleText, 'auth')) {
            return match (strtoupper((string) $method)) {
                'POST' => 'Authentication request submitted',
                'DELETE' => 'Authentication session closed',
                default => 'Processed authentication request',
            };
        }

        return match (strtoupper((string) $method)) {
            'POST' => "Created new record in {$module}",
            'PUT', 'PATCH' => "Updated record in {$module}",
            'DELETE' => "Deleted record from {$module}",
            default => "Processed {$module} request",
        };
    }

    private static function resolveModuleLabel(?string $model, ?string $routeName, ?string $url, $meta): string
    {
        $source = $model ?? $routeName ?? $url ?? data_get($meta, 'route') ?? 'Record';
        $segment = $source;

        if (is_string($source) && str_contains($source, '\\')) {
            $segment = class_basename($source);
        }

        if (is_string($segment)) {
            $segment = preg_replace('/\.(index|store|update|destroy|create|edit|show)$/', '', $segment) ?: $segment;
            $segment = str_replace(['-', '_', '/'], ' ', $segment);
            $segment = preg_replace('/\s+/', ' ', trim($segment)) ?: 'Record';
            return ucwords($segment);
        }

        return 'Record';
    }

    private static function normalizeAction(string $action): string
    {
        return strtolower(str_replace([' ', '-'], '_', trim($action)));
    }
}
