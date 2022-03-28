<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportListing implements FromCollection, WithHeadings
{
    public function __construct(public Collection $resultCollection, public array $selectedFields, public array $exclude)
    {
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return $this->resultCollection->map(function ($item, $key) {
            return $item->setHidden($this->exclude);
        });
    }

    public function headings(): array
    {
        $processedFields = [];
        if (count($this->resultCollection) > 0) {
            $firstRecord = $this->resultCollection->first();
            $tableName = $firstRecord->getTable();
            $fields = $firstRecord->setHidden($this->exclude)->toArray();

            foreach ($fields as $key => $field) {
                $keyArr = explode('_', $key);

                //Str::ucfirst(Str::replace('.', ' ', $key));


                $processedFields[Str::replace('.', '_', $key)]
                    = __(Str::replace(
                        $keyArr[0] . '_',
                        Str::ucfirst(Str::singular($keyArr[0])) . '.',
                        $key
                    ));
            }
        }

        return $processedFields; // array_diff($processedFields,['pivot']);
    }
}
