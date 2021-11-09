<?php

namespace Uccello\RecordManager\Support\Structure;

class Filter
{
    public $name;
    public $type;
    public $columns = [];
    public $default = false;
    public $readonly = false;

    /**
     * Constructor
     *
     * @param \stdClass|array|null $data
     */
    public function __construct($data = null)
    {
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
}
