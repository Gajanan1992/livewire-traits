<?php


namespace App\traits;

use Illuminate\Support\Facades\DB;
use Str;

trait ResetPagination
{
    public function updating()
    {
        $this->resetPage();
    }
}
