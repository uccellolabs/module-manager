<?php

namespace Uccello\RecordManager\Http\Livewire;

use Livewire\Component;
use Uccello\RecordManager\Facades\Module;

class RecordManager extends Component
{
    public $moduleName;

    public function render()
    {
        return view('record-manager::livewire.record-manager', [
            'module' => Module::getInstance($this->moduleName)
        ]);
    }
}
