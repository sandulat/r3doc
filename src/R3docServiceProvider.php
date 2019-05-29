<?php

declare(strict_types=1);

namespace Sandulat\R3doc;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class R3docServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->registerRoutes();

        $this->registerViews();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('r3doc.php'),
            ], 'r3doc-config');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/r3doc'),
            ], 'r3doc-assets');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'r3doc');

        $this->app->singleton('r3doc', static function () {
            return new R3doc();
        });
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    /**
     * Get the main route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration(): array
    {
        return [
            'namespace' => 'Sandulat\R3doc\Http\Controllers',
            'prefix' => config('r3doc.path'),
        ];
    }

    /**
     * Register the package views.
     *
     * @return array
     */
    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'r3doc');
    }
}
