<?php

namespace Uccello\ModuleManager\Support\Structure;

use Uccello\ModuleManager\Facades\Module as FacadesModule;
use Uccello\ModuleManager\Support\Structure\Module;

class Tab
{
    public $module;
    public $name;
    public $icon;
    public $blocks;

    /**
     * Constructor
     *
     * @param Uccello\ModuleManager\Support\Structure\Module $module
     * @param \stdClass|array|null $data
     */
    public function __construct(Module $module, $data = null)
    {
        $this->module = $module;
        $this->blocks = collect();

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
            throw new \Exception('First argument must be an object');
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
        return FacadesModule::trans('tab.'.$this->name, $this->module);
    }

    /**
     * Adds a new block.
     * Initialize blocks collection if necessary.
     * Convert stdClass to Block if necessary.
     *
     * @param \stdClass|array|\Uccello\ModuleManager\Support\Structure\Block $block
     *
     * @return \Uccello\ModuleManager\Support\Structure\Block
     */
    public function addBlock($block)
    {
        // Initialize blocks
        if (empty($this->blocks)) {
            $this->blocks = collect();
        }

        // Convert block if necessary
        if ($block instanceof Block === false) {
            $block = new Block($this, $block);
        }

        // Add block
        $this->blocks[] = $block;

        return $block;
    }

    /**
     * Checks if tab is visible in a view.
     * A block is visible if at least one block is visible.
     *
     * @param string $viewName
     *
     * @return boolean
     */
    public function isVisible(string $viewName)
    {
        $isVisible = false;

        if ($this->blocks) {
            foreach ($this->blocks as $block) {
                if ($block->isVisible($viewName)) {
                    $isVisible = true;
                    break;
                }
            }
        }

        return $isVisible;
    }
}
