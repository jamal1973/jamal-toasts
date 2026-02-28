<?php declare(strict_types=1);

namespace Jamal\Toasts;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Jamal\Toasts\View\Components\JamalToasts;

final class ToastsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/jamal_toasts.php', 'jamal_toasts');

        $this->app->singleton(ToastManager::class, function ($app) {
            return new ToastManager($app['session.store']);
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'jamal-toasts');

        // komponent: <x-jamal-toasts />
        Blade::component('jamal-toasts', JamalToasts::class);

        $this->publishes([
            __DIR__ . '/../config/jamal_toasts.php' => config_path('jamal_toasts.php'),
        ], 'jamal-toasts-config');

        $this->publishes([
            __DIR__ . '/../resources/assets/toasts.js'  => public_path('vendor/jamal-toasts/toasts.js'),
            __DIR__ . '/../resources/assets/toasts.css' => public_path('vendor/jamal-toasts/toasts.css'),
        ], 'jamal-toasts-assets');
    }
}