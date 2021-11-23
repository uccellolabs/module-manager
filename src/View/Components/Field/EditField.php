<?php

namespace Uccello\ModuleManager\View\Components\Field;

use Illuminate\View\Component;
use Uccello\ModuleManager\Facades\Module as FacadesModule;
use Uccello\ModuleManager\Support\Structure\Field;
use Uccello\ModuleManager\Support\Structure\Module;

class EditField extends Component
{
    public $value;

    public $viewName;

    private $module;
    private $field;
    private $record;

    /**
     * UCreate a new component instance.
     *
     * @param \Uccello\ModuleManager\Support\Structure\Module $module
     * @param \Uccello\ModuleManager\Support\Structure\Field $field
     * @param mixed $record
     */
    public function __construct(Module $module, Field $field, $record)
    {
        $this->module = $module;
        $this->field = $field;
        $this->record = $record;

        $this->retrieveFieldValue();
        $this->retrieveUitypeViewName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('module-manager::components.field.edit-field', [
            'module' => $this->module,
            'record' => $this->record,
            'field' => $this->field,
        ]);
    }

    private function retrieveFieldValue()
    {
        $this->value = $this->field->value($this->record);
    }

    private function retrieveUitypeViewName()
    {
        $module = $this->module;
        $this->viewName = FacadesModule::view(
            $module->package,
            $module,
            "uitype.edit.{$this->field->type}",
            "module-manager::modules.default.uitype.edit.string"
        );
    }
}
