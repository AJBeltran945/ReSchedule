<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home/month';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Main web routes with localization
        Route::middleware([
            'web',
            'localeSessionRedirect',
            'localizationRedirect',
            'localeViewPath',
            'localize',
        ])
            ->prefix(LaravelLocalization::setLocale())
            ->group(function () {

                Livewire::setUpdateRoute(function ($handle) {
                    return Route::post('/livewire/update', $handle);
                });

                Route::get('/', function () {
                    return view('frontend.welcome');
                })->name('welcome');

                require base_path('routes/web.php');
            });
    }
}
