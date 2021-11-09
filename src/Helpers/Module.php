<?php

namespace Uccello\ModuleManager\Helpers;

use Illuminate\Support\Facades\Cache;
use Uccello\ModuleManager\Support\Structure\Module as StructureModule;

class Module
{
    public function getInstance($moduleName)
    {
        $kernel = new \App\Modules\Kernel;
        return $kernel->getModuleInstance($moduleName);
    }

    /**
     * Retrieve prefix and translate the given message.
     * If the translation does not exist try to find a default one.
     * If no translation exists display only the key.
     *
     * Priority:
     * 1 - Translation overrided in app
     * 2 - Translation in package
     * 3 - Default translation overrided in app
     * 4 - No translation
     *
     * @param  string  $key
     * @param  \Uccello\ModuleManager\Support\Structure\Module|null  $module
     * @param  array   $replace
     * @param  string  $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    public function trans($key = null, ?StructureModule $module = null, $replace = [], $locale = null)
    {
        $translator = app('translator');

        if (is_null($key)) {
            return $translator;
        }

        // If $module is an instance of Module class, add a prefix before the key
        if (!is_null($module)) {
            // By default prefix is same as the module's name
            $prefix = $module->name . '.';

            // 1. Get translation in app
            // Compatibilty with new version of Laravel
            $translation = $translator->get($prefix . $key, $replace, $locale);

            if ($translation !== $prefix . $key) {
                return $translation;
            }

            // 2. Get translation in package
            if (!empty($module->package)) {
                // If a package name is defined add it before
                $prefix = $module->package . '::' . $prefix;

                // Compatibilty with new version of Laravel
                $translation = $translator->get($prefix . $key, $replace, $locale);

                if ($translation !== $prefix . $key) {
                    return $translation;
                }
            }

            // 3. Try with default translation in app
            $appDefaultTranslation = $translator->get('default.' . $key, $replace, $locale);

            if ($appDefaultTranslation !== 'default.' . $key) { // If default translation exists then use it
                return $appDefaultTranslation;
            }

            // 4. If translation does not exist, display only the key
            return $key;
        }

        // Default behaviour
        return $translator->get($key, $replace, $locale);
    }

    /**
     * Detects which view it must use and returns the evaluated view contents.
     *
     * Priority:
     * 1 - Module view overrided in app
     * 2 - Module view ovverrided in package
     * 3 - Default view overrided in app
     * 4 - Default view defined in package
     * 5 - Module view ovverrided in uccello
     * 6 - Default view defined in uccello
     * 7 - Fallback view if defined
     *
     * @param string $package
     * @param Module $module
     * @param string $viewName
     * @param string|null $fallbackView
     * @return string|null
     */
    public function view(?string $package, StructureModule $module, string $viewName, ?string $fallbackView = null): ?string
    {
        return Cache::remember(
            $package.'_'.$module->name.'_'.$viewName.'_'.$fallbackView,
            now()->addMinutes(10),
            function () use ($package, $module, $viewName, $fallbackView) {
                // Module view overrided in app
                $appModuleView = 'modules.' . $module->name . '.' . $viewName;

                // Default view overrided in app
                $appDefaultView = 'modules.default.' . $viewName;

                if (!empty($module->package)) {
                    // Module view ovverrided in package
                    $packageModuleView = $package . '::modules.' . $module->name . '.' . $viewName;

                    // Default view defined in package
                    $packageDefaultView = $package . '::modules.default.' . $viewName;
                }

                // Module view ovverrided in module-manager
                $managerModuleView = 'module-manager::modules.' . $module->name . '.' . $viewName;

                // Default view defined in module-manager
                $managerDefaultView = 'module-manager::modules.default.' . $viewName;

                $viewToInclude = null;
                if (view()->exists($appModuleView)) {
                    $viewToInclude = $appModuleView;
                } elseif (!empty($packageModuleView) && view()->exists($packageModuleView)) {
                    $viewToInclude = $packageModuleView;
                } elseif (view()->exists($appDefaultView)) {
                    $viewToInclude = $appDefaultView;
                } elseif (!empty($packageDefaultView) && view()->exists($packageDefaultView)) {
                    $viewToInclude = $packageDefaultView;
                } elseif (view()->exists($managerModuleView)) {
                    $viewToInclude = $managerModuleView;
                } elseif (view()->exists($managerDefaultView)) {
                    $viewToInclude = $managerDefaultView;
                } elseif (!is_null($fallbackView)) {
                    $viewToInclude = $fallbackView;
                }

                return $viewToInclude;
            }
        );
    }
}
