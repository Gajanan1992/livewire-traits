<?php

namespace App\traits;

trait ChangeViews
{
    public $display = 'list';

    public function setAction($action, $selected_id = null)
    {
        $this->resetInput();
        $this->display = $action;
        if (! is_null($selected_id)) {
            if ($action != 'details') {
                $this->edit($selected_id);
            } else {
                $this->selected_id = $selected_id;
            }
        } else {
            $this->actionChanged();
            $this->emit('actionChanged');
        }
    }

    public function save()
    {
        is_null($this->selected_id) ? $this->store() : $this->update();
    }

    abstract public function resetInput();

    public function actionChanged()
    {
        $this->selected_id = null;
    }
}
