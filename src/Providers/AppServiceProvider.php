<?php

namespace Uccello\ModuleManager\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Uccello\ModuleManager\Http\Livewire\ModuleManager;
use Uccello\ModuleManager\View\Components\Detail\Value;
use Uccello\ModuleManager\View\Components\Table\Search;
use Uccello\ModuleManager\View\Components\Table\Td;
use Uccello\ModuleManager\View\Components\Table\Th;

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
            'table-th' =>  Th::class,
            'table-td' =>  Td::class,
            'table-search' =>  Search::class,
            'detail-value' =>  Value::class,
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
