<?php

namespace Uccello\ModuleManager\Helpers;

class Module
{
    public function getInstance($moduleName)
    {
        $kernel = new \App\Modules\Kernel;
        return $kernel->getModuleInstance($moduleName);
    }
}
