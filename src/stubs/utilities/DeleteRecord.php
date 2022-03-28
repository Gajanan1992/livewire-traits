<?php


namespace App\traits;

use Illuminate\Support\Facades\DB;
use Str;

trait DeleteRecord
{
    public function deleteRecord($model, $id)
    {
        DB::beginTransaction();
        try {

            $model = 'App\\Models\\' . Str::studly(Str::singular($model));

            if ($this->isModelExist($model)) {
                if (isset($id)) {
                    $record = $model::where('id', $id)->first();
                    // dd($record);
                    if ($record != null) {
                        $record->forceDelete();
                        DB::rollBack();

                        $record = $model::where('id', $id)->first();
                        $record->delete();
                        DB::commit();
                    } else {
                        $record = $model::withTrashed()->where('id', $id)->first();
                        $record->forceDelete();
                        DB::commit();
                    }
                }

                session()->flash('message', 'Record deleted successfully.');
            } else {

                session()->flash('error', 'Something went wrong.');
            }
        } catch (\Exception $ex) {
            // dd($ex);
            DB::rollBack();
            session()->flash('error', 'Can not delete the record');
        }
    }

    public function isModelExist($model)
    {

        if (!is_subclass_of($model, 'Illuminate\Database\Eloquent\Model')) {
            return false;
        }

        return true;
    }
}
