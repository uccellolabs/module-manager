<?php

namespace Uccello\ModuleManager\Support\Structure;

use Uccello\ModuleManager\Facades\Module;

class Field
{
    public $module;
    public $block;
    public $name;
    public $column;
    public $type = 'string';
    public $visible = true;
    public $required = false;
    public $rules;
    public $info;
    public $options;

    /**
     * Constructor
     *
     * @param Uccello\ModuleManager\Support\Structure\Block $block
     * @param \stdClass|array|null $data
     */
    public function __construct(Block $block, $data = null)
    {
        $this->module = $block->module;
        $this->block = $block;

        if ($data === null || is_object($data) || is_array($data)) {
            // Convert to stdClass if necessary
            if (is_array($data)) {
                $data = json_decode(json_encode($data));
            }

            // Set data
            foreach ($data as $key => $val) {
                $this->{$key} = $val;
            }

            // Set default column
            if (empty($this->column)) {
                $this->column = $this->name;
            }
        } else {
            throw new \Exception('First argument must be an object or an array');
        }
    }

    /**
     * Getter to retrieve an attribute.
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function __get(string $attribute)
    {
        if ($attribute === 'label') {
            return $this->label();
        }

        return $this->{$attribute};
    }

    public function label()
    {
        return Module::trans('field.'.$this->name, $this->module);
    }

    /**
     * Checks if field is visible in a view.
     *
     * @param string $viewName
     *
     * @return boolean
     */
    public function isVisible(string $viewName)
    {
        $isVisible = false;

        if ($viewName === 'everywhere') {
            $isVisible = $this->isVisibleEverywhere();
        } elseif ($viewName === 'create') {
            $isVisible = $this->isVisibleInCreateView();
        } elseif ($viewName === 'edit') {
            $isVisible = $this->isVisibleInEditView();
        } elseif ($viewName === 'detail') {
            $isVisible = $this->isVisibleInDetailView();
        } elseif ($viewName === 'list') {
            $isVisible = $this->isVisibleInListView();
        } else {
            throw "View name $viewName is invalid";
        }

        return $isVisible;
    }

    /**
     * Check if field is visible everywhere
     *
     * @return boolean
     */
    public function isVisibleEverywhere()
    {
        return $this->visible === true
            ||
            (
                $this->isVisibleInCreateView()
                &&
                $this->isVisibleInEditView()
                &&
                $this->isVisibleInDetailView()
                &&
                $this->isVisibleInListView()
            );
    }

    /**
     * Check if field is visible in create view.
     *
     * @return boolean
     */
    public function isVisibleInCreateView()
    {
        return $this->visible === true
            ||
            (
                is_object($this->visible)
                &&
                optional($this->visible)->create === true
            );
    }

    /**
     * Check if field is visible in edit view.
     *
     * @return boolean
     */
    public function isVisibleInEditView()
    {
        return $this->visible === true
            ||
            (
                is_object($this->visible)
                &&
                optional($this->visible)->edit === true
            );
    }

    /**
     * Check if field is visible in detail view.
     *
     * @return boolean
     */
    public function isVisibleInDetailView()
    {
        return $this->visible === true
            ||
            (
                is_object($this->visible)
                &&
                optional($this->visible)->detail === true
            );
    }

    /**
     * Check if field is visible in list view.
     *
     * @return boolean
     */
    public function isVisibleInListView()
    {
        return $this->visible === true
            ||
            (
                is_object($this->visible)
                &&
                optional($this->visible)->list === true
            );
    }

    /**
     * Return column name according to the following priorities:
     * 1 - Column name defined in the field structure
     * 2 - Field type default column name
     *
     * @return string
     */
    public function column()
    {
        if (!empty($this->column)) {
            $column = $this->column;
        } else {
            $column = $this->type === 'entity' ? $this->name.'_id' : $this->name;
        }

        return $column;
    }

    /**
     * Return uitype
     *
     * @return object|string|null
     */
    public function uitype()
    {
        return $this->type;
    }

    /**
     * Return formatted value
     *
     * @param mixed $record
     *
     * @return string
     */
    public function value($record)
    {
        return $record->{$this->column};

        // $uitypeInstance = $this->getUitypeInstance();

        // return $uitypeInstance ? $uitypeInstance->value($this, $record) : '';
    }

    /**
     * Return raw value
     *
     * @param mixed $record
     *
     * @return mixed|null
     */
    public function rawValue($record)
    {
        $uitypeInstance = $this->getUitypeInstance();

        return $uitypeInstance ? $uitypeInstance->rawValue($this, $record) : null;
    }

    /**
     * Makes an instance of Uitype
     *
     * @return mixed|null
     */
    private function getUitypeInstance()
    {
        $instance = null;

        $uitype = $this->uitype();
        if (!empty($uitype)) {
            $uitypeClass = $uitype->class;
            $instance = new $uitypeClass;
        }

        return $instance;
    }
}
