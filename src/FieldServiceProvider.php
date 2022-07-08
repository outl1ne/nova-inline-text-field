<?php

namespace Outl1ne\NovaInlineTextField;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Outl1ne\NovaInlineTextField\Http\Controllers\NovaInlineTextFieldController;

class FieldServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('inline-text-field', __DIR__ . '/../dist/js/entry.js');
            Nova::style('inline-text-field', __DIR__ . '/../dist/css/entry.css');
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
