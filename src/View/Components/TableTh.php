<?php

namespace Uccello\ModuleManager\View\Components;

use Illuminate\View\Component;

class TableTh extends Component
{
    public $label;
    public $field;
    public $sortField;
    public $sortOrder;
    public $sortable;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $field, $sortField, $sortOrder, $sortable = false)
    {
        $this->label = $label;
        $this->field = $field;
        $this->sortField = $sortField;
        $this->sortOrder = $sortOrder;
        $this->sortable = $sortable;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('module-manager::components.table-th');
    }
}
