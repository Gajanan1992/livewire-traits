<?php

namespace App\traits;

trait HasDynamicPagination
{
    /**
     * set the default no of records per page
     *
     */
    public $numberOfRecords = 'all';

    /**
     * set the default no of records per page
     *
     */
    public function changeNumberOfRecords()
    {
        if ($this->numberOfRecords == 'all') {
            session()->flash('error', 'Loading more than 100 records may take time.');
        }
    }
}
