<?php

namespace Uccello\ModuleManager\Http\Livewire;

use Livewire\Wireable;

class Record implements Wireable
{
    public $record;

    public function __construct($record)
    {
        $this->record = $record;

        dd($record);
    }

    public function toLivewire()
    {
        return $this->record;
    }

    public static function fromLivewire($value)
    {
        return new static($value);
    }
}
