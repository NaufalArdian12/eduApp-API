<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (app()->environment('production')) {

            config([
                'session.driver' => 'database',
                'session.connection' => env('SESSION_CONNECTION', config('database.default')),
                'session.table' => env('SESSION_TABLE', 'sessions'),
                'session.domain' => '.eduapp-api-master-xgvx7r.laravel.cloud',
                'session.secure' => true,
                'session.same_site' => 'lax',
            ]);
        }
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('uploads', function (Request $request) {
            return Limit::perHour(10)->by($request->user()?->id);
        });

        RateLimiter::for('auth', function (Request $request) {
            $key = sprintf('auth:%s|%s', $request->ip(), $request->input('email', ''));

            return Limit::perMinute(5)->by($key)->response(function () {
                return response()->json([
                    'ok' => false,
                    'error' => [
                        'code' => 'TOO_MANY_REQUESTS',
                        'message' => 'Too many attempts. Please try again in a minute.',
                    ],
                ], 429);
            });
        });

    }

}
