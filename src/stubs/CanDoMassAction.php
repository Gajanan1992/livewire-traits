<?php


namespace App\traits;


use App\Models\User;
use App\Notifications\MassNotification;
use Illuminate\Support\Facades\Notification;
use JetBrains\PhpStorm\NoReturn;
use Str;

trait CanDoMassAction
{
    public array $selectedAll = [];
    public array $listedModels = [];
    public bool $checkAll = false;

    #[NoReturn]
    public function excludeFromGroupEmail($modelName)
    {

        if ($this->checkAll)
            $this->selectedAll = $this->listedModels;

        if (empty($this->selectedAll)) {
            session()->flash('error', __('no records selected'));
        }
        $model = 'App\\Models\\' . $modelName;
        $model = new $model();
        $updatedRecords = $model->whereIn('id', $this->selectedAll)->update(['exclude_group_email' => 1]);
        $this->selectedAll = [];
        $this->checkAll = false;
        session()->flash('message', $updatedRecords . ' ' . __('records updated'));
    }

    public function toggleSingle($modelName, $id)
    {
        $model = 'App\\Models\\' . $modelName;
        $model = new $model();
        $model = $model->find($id);
        if ($model->exclude_group_email == 0)
            $model->update(['exclude_group_email' => 1]);
        else
            $model->update(['exclude_group_email' => 0]);
        session()->flash('message', __('Record Successfully updated.'));
    }

    public function groupCommunication($selectedUsers, $subject, $mailBody)
    {
        $users = User::whereIn('id', $selectedUsers)->where('exclude_group_email', 0)->get();
        if (!is_null($users)) {
            Notification::send($users, new MassNotification($subject, $mailBody));
            session()->flash('message', 'Messages will be sent');
        }
    }

    public function massDelete($selectedIds, $model)
    {
        // dd($model);

        $model = 'App\\Models\\' . Str::studly(Str::singular($model));

        if ($this->isModelExist($model)) {

            for ($i = 0; $i < count($selectedIds); $i++) {
                $record = $model::where('id', $selectedIds[$i])->first();
                if ($record) {
                    $record->delete();
                }
            }
            $this->emit('recordsDeleted');

            session()->flash('message', 'Record deleted successfully.');
        } else {
            session()->flash('error', 'Something went wrong.');
        }
    }
}
