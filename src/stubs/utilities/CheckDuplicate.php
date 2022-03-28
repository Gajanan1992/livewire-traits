<?php

namespace App\traits;

use Illuminate\Support\Facades\Cache;

trait CheckDuplicate
{
    use HasUserTree;

    public function checkDuplicate($model, $fields, $selected_id = null)
    {
        $parentTree = Cache::remember('parentTree_'.session()->getId(), 600, fn () => $this->getParentTree(auth()->user()));
        $userTree = $this->getUserIds(); //Cache::remember('userTree', 600, fn() => $this->getUserTree([auth()->id()], [auth()->id()]));
        $path = 'App\\Models\\'.$model;
        $query = $path::where($fields)->whereIn('created_by', array_merge($parentTree, $userTree));

        if (! is_null($selected_id)) {
            $query = $query->where('id', '!=', $selected_id);
        }

        return $query->count();
    }

    public function duplicateErrorMessage($variable = null)
    {
        session()->flash('error', __('duplicate_record'));
    }

    public function operatioFailedErrorMessage($variable = null)
    {
        if (is_null($variable)) {
            session()->flash('error',  __('operation_failed'));
        } else {
            session()->flash('error',  $variable);
        }
    }

    public function successMessage($msg, $variable = null)
    {
        session()->flash('message', $msg);
    }

    abstract public function getDuplicatecolumns();
}
