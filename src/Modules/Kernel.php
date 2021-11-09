<?php

namespace Uccello\RecordManager\Modules;

class Kernel
{
    protected $modules = [];

    public function getModuleInstance($name)
    {
        if (!empty($this->modules[$name])) {
            return new $this->modules[$name];
        }

        throw new \Exception("The '$name' module is not defined in \App\Modules\Kernel.php");
    }
}
