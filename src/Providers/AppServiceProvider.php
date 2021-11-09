<?php

namespace Uccello\RecordManager\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Uccello\RecordManager\Http\Livewire\RecordManager;

/**
 * App Service Provider
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        // Views
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'record-manager');

        // Translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'record-manager');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/uccello/record-manager'),
        ], 'record-manager-assets');

        // Publish config
        $this->publishes([
            __DIR__.'/../../config/record-manager.php' => config_path('record-manager.php')
        ], 'record-manager-config');

        // Blade::components([
        //     //
        // ], 'rm');

        Livewire::component('record-manager', RecordManager::class);
    }

    public function register()
    {
        // Config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/record-manager.php',
            'record-manager'
        );
    }
}
