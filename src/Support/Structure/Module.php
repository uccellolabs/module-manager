<?php

namespace Uccello\RecordManager\Support\Structure;

use Illuminate\Support\Str;

class Module
{
    public $name;
    public $model;
    public $icon = 'extension';
    public $package = null;
    public $admin = false;
    public $required = false;

    public function name()
    {
        return property_exists($this, 'name') ? $this->name : Str::kebab(self::class);
    }

    public function isAdminModule()
    {
        return $this->admin;
    }

    public function isRequiredInWorkspace()
    {
        return $this->required;
    }

    public function tabs()
    {
        return [];
    }

    public function filters()
    {
        return [];
    }

    public function relatedLists()
    {
        return [];
    }

    public function blocks()
    {
        $blocks = collect();
        foreach ($this->tabs() as $tab) {
            foreach ($tab->blocks as $block) {
                $blocks[] = $block;
            }
        }
        return $blocks;
    }

    public function fields()
    {
        $fields = collect();
        foreach ($this->tabs() as $tab) {
            foreach ($tab->blocks as $block) {
                foreach ($block->fields as $field) {
                    $fields[] = $field;
                }
            }
        }
        return $fields;
    }
}
