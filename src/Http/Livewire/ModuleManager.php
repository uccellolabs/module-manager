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
    public $displayedFieldNames = [];
    public $selection = [];
    public $isDetailViewOpen = false;
    public $recordId;

    protected $queryString = [
        'sortField',
        'sortOrder' => ['except' => 'asc'],
        'search'
    ];

    public function mount()
    {
        $this->getdisplayedFieldNames();
    }

    public function render()
    {
        return view('module-manager::livewire.module-manager', [
            'module' => $this->getModule(),
            'filter' => $this->getDefaultFilter(),
            'records' => $this->getRecords(),
            'currentRecord' => $this->loadCurrentRecord(),
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
        $fieldNames = collect($this->displayedFieldNames);

        if ($fieldNames->contains($fieldName)) {
            $this->displayedFieldNames = $fieldNames->filter(function ($name) use ($fieldName) {
                return $name !== $fieldName;
            })->toArray();
        } else {
            $this->displayedFieldNames[] = $fieldName;
        }
    }

    public function deleteSelectedRecords()
    {
        $recordModel = $this->getRecordModel();

        $recordModel->destroy($this->selection);

        $this->selection = [];
        $this->resetPage();
    }

    public function showDetailView($recordId)
    {
        $this->recordId = $recordId;
        $this->isDetailViewOpen = true;
    }

    private function getModule()
    {
        return Module::getInstance($this->moduleName);
    }

    private function getDefaultFilter()
    {
        return $this->getModule()->filters->where('type', 'list')->first();
    }

    private function getRecords()
    {
        $recordModel = $this->getRecordModel();

        $query = $recordModel::query();

        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortOrder);
        }

        if (!empty($this->search)) {
            foreach ($this->search as $key => $val) {
                $query->where($key, 'like', "%$val%");
            }
        }

        return $query->paginate($this->length);
    }

    private function loadCurrentRecord()
    {
        if (!$this->recordId) {
            return;
        }

        $recordModel = $this->getRecordModel();

        return $recordModel->find($this->recordId);
    }

    private function getRecordModel()
    {
        $module = $this->getModule();

        $model = $module->model;
        $recordModel = new $model;

        return $recordModel;
    }

    /**
     * Detect fields to display by default
     *
     * @return array
     */
    private function getdisplayedFieldNames()
    {
        $this->displayedFieldNames = [];

        if (empty($this->displayedFieldNames)) {
            $this->displayedFieldNames = $this->getDefaultFilter()->columns;
        }

        return $this->displayedFieldNames;
    }
}
