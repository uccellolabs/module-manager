<?php

namespace Uccello\ModuleManager\View\Components\Table;

use Illuminate\View\Component;
use Uccello\ModuleManager\Support\Structure\Field;
use Uccello\ModuleManager\Support\Structure\Module;

class Th extends Component
{
    public $fieldName;
    public $sortFieldName;
    public $sortOrder;

    private $module;
    private $field;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Module $module, Field $field, $sortFieldName = null, $sortOrder = null)
    {
        $this->module = $module;
        $this->field = $field;
        $this->fieldName = $field->name;
        $this->sortFieldName = $sortFieldName;
        $this->sortOrder = $sortOrder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('module-manager::components.table.th', [
            'module' => $this->module,
            'field' => $this->field
        ]);
    }
}
