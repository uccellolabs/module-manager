<?php

namespace Uccello\ModuleManager\Support\Structure;

use Illuminate\Support\Str;
use Uccello\ModuleManager\Facades\Module as FacadesModule;

class Module
{
    public $name;
    public $model;
    public $icon = 'extension';
    public $package = null;
    public $admin = false;
    public $required = false;
    private $tabs;
    private $filters;
    private $relatedLists;

    public function __construct()
    {
        $this->tabs = collect();
        $this->filters = collect();
        $this->relatedLists = collect();

        $this->initStructure();
    }

    public function initStructure()
    {
        //
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
        if (method_exists($this, $attribute)) { // Call method if exists
            return $this->{$attribute}();
        }

        return $this->{$attribute};
    }

    public function name()
    {
        return property_exists($this, 'name') ? $this->name : Str::kebab(self::class);
    }

    public function label()
    {
        return FacadesModule::trans($this->name, $this);
    }

    public function isAdminModule()
    {
        return $this->admin;
    }

    public function isRequiredInWorkspace()
    {
        return $this->required;
    }

    /**
     * Adds a new tab.
     * Initialize tabs collection if necessary.
     * Convert stdClass to Tab if necessary.
     *
     * @param \stdClass|array|\Uccello\ModuleManager\Support\Structure\Tab $tab
     *
     * @return \Uccello\ModuleManager\Support\Structure\Tab
     */
    public function addTab($tab)
    {
        // Initialize tabs
        if (empty($this->tabs)) {
            $this->tabs = collect();
        }

        // Convert tab if necessary
        if ($tab instanceof Tab === false) {
            $tab = new Tab($this, $tab);
        }

        // Add tab
        $this->tabs[] = $tab;

        return $tab;
    }

    /**
     * Adds a new filter.
     * Initialize filters collection if necessary.
     * Convert stdClass to Filter if necessary.
     *
     * @param \stdClass|array|\Uccello\ModuleManager\Support\Structure\Filter $filter
     *
     * @return \Uccello\ModuleManager\Support\Structure\Filter
     */
    public function addFilter($filter)
    {
        // Initialize filters
        if (empty($this->filters)) {
            $this->filters = collect();
        }

        // Convert tab if necessary
        if ($filter instanceof Filter === false) {
            $filter = new Filter($this, $filter);
        }

        // Add tab
        $this->filters[] = $filter;

        return $filter;
    }

    /**
     * Adds a new relatedList.
     * Initialize relatedList collection if necessary.
     * Convert stdClass to RelatedList if necessary.
     *
     * @param \stdClass|array|\Uccello\ModuleManager\Support\Structure\RelatedList $relatedList
     *
     * @return \Uccello\ModuleManager\Support\Structure\RelatedList
     */
    public function addRelatedList($relatedList)
    {
        // Initialize relatedLists
        if (empty($this->relatedLists)) {
            $this->relatedLists = collect();
        }

        // Convert relatedList if necessary
        if ($relatedList instanceof RelatedList === false) {
            $relatedList = new RelatedList($this, $relatedList);
        }

        // Add relatedList
        $this->relatedLists[] = $relatedList;

        return $relatedList;
    }

    public function blocks()
    {
        $blocks = collect();
        foreach ($this->tabs as $tab) {
            $blocks = $blocks->merge($tab->blocks);
        }
        return $blocks;
    }

    public function fields()
    {
        $fields = collect();
        foreach ($this->tabs as $tab) {
            foreach ($tab->blocks as $block) {
                $fields = $fields->merge($block->fields);
            }
        }
        return $fields;
    }

    public function blocksVisibleInDetailView()
    {
        return $this->blocks()->filter(function ($block) {
            return $block->isVisibleInDetailView();
        });
    }

    public function fieldsVisibleInDetailView()
    {
        return $this->fields()->filter(function ($field) {
            return $field->isVisibleInDetailView();
        });
    }
}
