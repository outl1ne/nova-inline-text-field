<?php

namespace OptimistDigital\NovaInlineTextField;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use OptimistDigital\NovaInlineTextField\Http\Controllers\NovaInlineTextFieldController;

class NovaInlineTextFieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('inline-text', __DIR__ . '/../dist/js/field.js');
        });

        $this->app->booted(function () {
            $this->routes();
        });
    }

    public function register()
    {
        //
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) return;

        Route::middleware(['nova'])->prefix('nova-vendor/nova-inline-text-field')->group(function () {
            Route::post('/update/{resource}', [NovaInlineTextFieldController::class, 'update']);
        });
    }
}
