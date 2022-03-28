<?php

namespace App\traits;

trait ResetPagination
{
    public function updating()
    {
        $this->resetPage();
    }
}
