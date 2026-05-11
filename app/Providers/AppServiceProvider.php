<?php

namespace App\Providers;

use App\Models\SystemLog;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use App\Observers\SystemLogObserver;
use App\Services\SystemLogService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Paginator::defaultView('pagination::default');

        $this->registerModelObservers();
        $this->registerAuthEventLogging();
    }

    private function registerModelObservers(): void
    {
        $modelsPath = app_path('Models');

        if (!File::isDirectory($modelsPath)) {
            return;
        }

        foreach (File::files($modelsPath) as $file) {
            $candidate = 'App\\Models\\' . $file->getFilenameWithoutExtension();

            if (!class_exists($candidate) || $candidate === SystemLog::class) {
                continue;
            }

            $candidate::observe(SystemLogObserver::class);
        }
    }

    private function registerAuthEventLogging(): void
    {
        Event::listen(Login::class, function (Login $event) {
            SystemLogService::log([
                'action' => 'login',
                'description' => 'User logged in',
                'user_id' => $event->user->id,
                'user_email' => $event->user->email,
                'user_role' => $event->user->role?->role_name,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
                'meta' => ['guard' => $event->guard],
            ]);
        });

        Event::listen(Logout::class, function (Logout $event) {
            SystemLogService::log([
                'action' => 'logout',
                'description' => 'User logged out',
                'user_id' => $event->user?->id,
                'user_email' => $event->user?->email,
                'user_role' => $event->user?->role?->role_name,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        });
    }
}
