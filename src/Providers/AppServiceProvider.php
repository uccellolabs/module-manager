<?php

namespace Uccello\ModuleManager\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Uccello\ModuleManager\View\Components\TableTh;
use Uccello\ModuleManager\Http\Livewire\ModuleManager;

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
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'module-manager');

        // Translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'module-manager');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/uccello/module-manager'),
        ], 'module-manager-assets');

        // Publish config
        $this->publishes([
            __DIR__.'/../../config/module-manager.php' => config_path('module-manager.php')
        ], 'module-manager-config');

        Blade::components([
            'table-th' =>  TableTh::class,
        ], 'uc');

        Livewire::component('module-manager', ModuleManager::class);
    }

    public function register()
    {
        // Config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/module-manager.php',
            'module-manager'
        );

        // Helper
        App::bind('module', function () {
            return new \Uccello\ModuleManager\Helpers\Module;
        });
    }
}
