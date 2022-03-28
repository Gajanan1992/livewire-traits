<?php


namespace App\traits;


trait CanSearch
{
    protected $query;
    abstract public function search();
    abstract public function resetSearch();
}
