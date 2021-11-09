<?php

namespace Uccello\ModuleManager\Support\Structure;

class RelatedList
{
    public $module;
    public $name;
    public $relatedModule;

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
            throw new \Exception('First argument must be an object');
        }
    }
}
