<?php

namespace Uccello\ModuleManager\View\Components\Table;

use Illuminate\View\Component;
use Uccello\Core\Facades\Uccello;
use Uccello\ModuleManager\Facades\Module as FacadesModule;
use Uccello\ModuleManager\Support\Structure\Field;
use Uccello\ModuleManager\Support\Structure\Module;

class Search extends Component
{
    public $value;

    public $viewName;

    private $module;
    private $field;

    /**
     * UCreate a new component instance.
     *
     * @param \Uccello\ModuleManager\Support\Structure\Module $module
     * @param \Uccello\ModuleManager\Support\Structure\Field $field
     */
    public function __construct(Module $module, Field $field)
    {
        $this->module = $module;
        $this->field = $field;

        $this->retrieveSearchValue();
        $this->retrieveUitypeViewName();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('module-manager::components.table.search', [
            'module' => $this->module,
            'field' => $this->field
        ]);
    }

    private function retrieveSearchValue()
    {
        $this->value = '';
    }

    private function retrieveUitypeViewName()
    {
        $module = $this->module;

        $this->viewName = FacadesModule::view(
            $module->package,
            $module,
            "uitype.search.{$this->field->type}",
            "module-manager::modules.default.uitype.search.string"
        );
    }
}
