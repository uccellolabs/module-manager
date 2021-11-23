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
    public $isDetailing = false;
    public $isEditing = false;
    public $isCreating = false;
    public $recordId;

    public object $currentRecord;

    protected $queryString = [
        'sortField',
        'sortOrder' => ['except' => 'asc'],
        'search',
        'recordId',
    ];

    protected function rules()
    {
        $rules = [];

        $module = $this->getModule();

        foreach ($module->fields as $field) {
            if (($this->isEditing && !$field->isVisibleInEditView()) || ($this->isCreating && !$field->isVisibleInCreateView())) {
                continue;
            }

            if (!empty($field->rules)) {
                $allRules = implode('|', $field->rules);
                $rules['currentRecord.'.$field->column] = str_replace('%id%', $this->currentRecord->getKey(), $allRules);
            } elseif ($field->required ?? false) {
                $rules['currentRecord.'.$field->column] = 'required';
            } else {
                $rules['currentRecord.'.$field->column] = 'string';
            }
        }

        return $rules;
    }

    public function mount()
    {
        $this->getDisplayedFieldNames();

        $this->loadCurrentRecord();

        if ($this->recordId) {
            $this->isDetailing = true;
        }
    }

    public function render()
    {
        return view('module-manager::livewire.module-manager', [
            'module' => $this->getModule(),
            'filter' => $this->getDefaultFilter(),
            'records' => $this->getRecords(),
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

    public function deleteCurrentRecord()
    {
        $this->currentRecord->delete();

        $this->isEditing = false;
        $this->isDetailing = false;
        $this->isCreating = false;

        $this->reset('recordId');
        $this->resetPage();
    }

    public function showDetailView($recordId)
    {
        $this->recordId = $recordId;
        $this->loadCurrentRecord();

        $this->isDetailing = true;
        $this->isEditing = false;
        $this->isCreating = false;
    }

    public function showEditView($recordId)
    {
        $this->recordId = $recordId;
        $this->loadCurrentRecord();

        $this->isDetailing = false;
        $this->isEditing = true;
        $this->isCreating = false;
    }

    public function showCreateView()
    {
        $this->recordId = null;

        $recordModel = $this->getRecordModel();

        $this->currentRecord = new $recordModel;

        $this->isDetailing = false;
        $this->isEditing = false;
        $this->isCreating = true;
    }

    public function save()
    {
        $this->validate();

        $this->currentRecord->save();

        $this->showDetailView($this->currentRecord->getKey());
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

        $record = $recordModel->find($this->recordId);
        if ($record) {
            $this->currentRecord = $record;
        }
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
    private function getDisplayedFieldNames()
    {
        $this->displayedFieldNames = [];

        if (empty($this->displayedFieldNames)) {
            $this->displayedFieldNames = $this->getDefaultFilter()->columns;
        }

        return $this->displayedFieldNames;
    }
}
