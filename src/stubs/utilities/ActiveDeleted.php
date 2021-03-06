<?php

namespace App\traits;

trait ActiveDeleted
{
    use Sortable;


    public $is_active = 1;
    public $perPage = 10;

    /**
     * @return mixed
     * get the active or deleted records
     *
     */
    public function active($status)
    {
        $this->is_active = $status;
    }

    public function getQuery($query)
    {
        if (is_null($this->sortableField)) {
            $this->setSortableField();
        }

        if ($this->is_active == 0) {
            $query = $query->onlyTrashed();
        }

        if (! is_null($this->sortableField)) {
            $query = $query->orderBy($this->sortableField, $this->sortableDirection);
        }

        // if ($this->numberOfRecords != 'all')
        //     $query = $query->limit($this->numberOfRecords);

        return $query;
    }
}
