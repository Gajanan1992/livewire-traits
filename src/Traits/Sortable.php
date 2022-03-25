<?php

namespace App\traits;

trait Sortable
{
    public $sortableField = null;
    public $sortableDirection = 'ASC';

    abstract public function setSortableField();

    public function sort($field)
    {
        $this->sortableDirection = ($this->sortableDirection === 'ASC') ? 'DESC' : 'ASC';
        $this->sortableField = $field;
    }
}
