<?php

namespace Uccello\ModuleManager\Support\Structure;

class Filter
{
    public $module;
    public $name;
    public $type;
    public $columns = [];
    public $default = false;
    public $readonly = false;

    /**
     * Constructor
     *
     * @param Uccello\ModuleManager\Support\Structure\Module $module
     * @param \stdClass|array|null $data
     */
    public function __construct(Module $module, $data = null)
    {
        $this->module = $module;

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

    public function fields()
    {
        return $this->module->fields()->whereIn('column', $this->columns)->map(function ($field) {
            return new Field($this->module, $field);
        });
    }
}
