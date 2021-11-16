<?php

namespace Uccello\ModuleManager\Support\Structure;

use Uccello\ModuleManager\Facades\Module;

class Block
{
    public $module;
    public $tab;
    public $name;
    public $icon;
    public $closed = false;
    public $info;
    public $fields;

    /**
     * Constructor
     *
     * @param Uccello\ModuleManager\Support\Structure\Module $module
     * @param \stdClass|array|null $data
     */
    public function __construct(Tab $tab, $data = null)
    {
        $this->module = $tab->module;
        $this->tab = $tab;
        $this->fields = collect();

        if ($data === null || is_object($data) || is_array($data)) {
            // Convert to stdClass if necessary
            if (is_array($data)) {
                $data = json_decode(json_encode($data));
            }

            // Set data
            foreach ($data as $key => $val) {
                $this->{$key} = $val;
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
        return Module::trans('block.'.$this->name, $this->module);
    }

    /**
     * Adds a new field.
     * Initialize fields collection if necessary.
     * Convert stdClass to Field if necessary.
     *
     * @param \stdClass|array|\Uccello\ModuleManager\Support\Structure\Field $field
     *
     * @return \Uccello\ModuleManager\Support\Structure\Field
     */
    public function addField($field)
    {
        // Initialize fields
        if (empty($this->fields)) {
            $this->fields = collect();
        }

        // Convert field if necessary
        if ($field instanceof Field === false) {
            $field = new Field($this, $field);
        }

        // Add field
        $this->fields[] = $field;

        return $field;
    }

    /**
     * Checks if block is visible in a view.
     * A block is visible if at least one field is visible.
     *
     * @param string $viewName
     *
     * @return boolean
     */
    public function isVisible(string $viewName)
    {
        $isVisible = false;

        foreach ($this->fields as $field) {
            $field = new Field($this->module, $field);
            if ($field->isVisible($viewName)) {
                $isVisible = true;
                break;
            }
        }

        return $isVisible;
    }

    /**
     * Check if field is visible in create view.
     *
     * @return boolean
     */
    public function isVisibleInCreateView()
    {
        return $this->isVisible('create');
    }

    /**
     * Check if field is visible in edit view.
     *
     * @return boolean
     */
    public function isVisibleInEditView()
    {
        return $this->isVisible('edit');
    }

    /**
     * Check if field is visible in detail view.
     *
     * @return boolean
     */
    public function isVisibleInDetailView()
    {
        return $this->isVisible('detail');
    }

    public function fieldsVisibleInDetailView()
    {
        return $this->fields->filter(function ($field) {
            return $field->isVisibleInDetailView();
        });
    }
}
