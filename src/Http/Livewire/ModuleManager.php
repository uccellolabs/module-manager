<?php

namespace Uccello\ModuleManager\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Uccello\ModuleManager\Facades\Module;

class ModuleManager extends Component
{
    use WithPagination;

    public $moduleName;
    public $sortField;
    public $sortOrder;
    public $search;
    public $length = 15;
    public $visibleFields = [];

    protected $queryString = [
        'sortField',
        'sortOrder' => ['except' => 'asc'],
        'search'
    ];

    public function mount()
    {
        $this->getVisibleFields();
    }

    public function render()
    {
        return view('module-manager::livewire.module-manager', [
            'module' => $this->getModule(),
            'filter' => $this->getDefaultFilter(),
            'records' => $this->getRecords()
        ]);
    }

    /**
     * Return view to use for pagination
     *
     * @return string
     */
    public function paginationView()
    {
        return 'module-manager::livewire.pagination.tailwind';
    }

    /**
     * Return view to use for simple pagination
     *
     * @return string
     */
    public function paginationSimpleView()
    {
        return 'module-manager::livewire.pagination.simple-tailwind';
    }

    public function changeSortOrder($field)
    {
        if ($field !== $this->sortField) {
            $this->sortOrder = 'asc';
            $this->sortField = $field;
        } else {
            if ($this->sortOrder === 'asc') {
                $this->sortOrder = 'desc';
            } else {
                $this->sortOrder = null;
                $this->sortField = null;
            }
        }
    }

    /**
     * Toggle field visibility and emit event
     *
     * @param string $fieldName
     *
     * @return void
     */
    public function toggleFieldVisibility($fieldName)
    {
        $fieldNames = collect($this->visibleFields);

        if ($fieldNames->contains($fieldName)) {
            $this->visibleFields = $fieldNames->filter(function ($name) use ($fieldName) {
                return $name !== $fieldName;
            })->toArray();
        } else {
            $this->visibleFields[] = $fieldName;
        }
    }

    private function getModule()
    {
        return Module::getInstance($this->moduleName);
    }

    private function getDefaultFilter()
    {
        return $this->getModule()->filters()->where('type', 'list')->first();
    }

    private function getRecords()
    {
        $module = $this->getModule();

        $model = $module->model;
        $recordModel = new $model;

        $query = $recordModel::query();

        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortOrder);
        }

        return $query->paginate($this->length);
    }

    /**
     * Detect fields to display by default
     *
     * @return array
     */
    private function getVisibleFields()
    {
        $this->visibleFields = [];

        if (empty($this->visibleFields)) {
            $this->visibleFields = $this->getDefaultFilter()->columns;
        }

        return $this->visibleFields;
    }
}
