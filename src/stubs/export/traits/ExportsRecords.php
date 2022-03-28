<?php

namespace App\traits;

use App\Exports\ExportListings;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use SnappyPdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait ExportsRecords
{
    use HasPlaceholders;
    use CanSearch;
    public $fields = [];
    public $models = [];
    public $modelLabels = [];
    public $selectedFields = [];
    public $excludedFields = ['pivot'];
    public $type = 'excel';

    abstract public function setModels();

    public function getFields($excludedColumns = [])
    {
        foreach ($this->models as $table => $model) {
            $this->fields[$table] = $this->getColumnList($model, false, $excludedColumns);
        }
    }

    public function exportExcel($selectedFields): BinaryFileResponse|null
    {
        $this->selectedFields = array_unique(array_merge($this->selectedFields, $selectedFields));
        $this->search(false);
        $records = $this->query->get();
        $selectedFields = $this->selectedFields;
        // $this->resetInput();
        if ($records) {
            return Excel::download(new ExportListing($records, $selectedFields, $this->processExcludeFields($this->excludedFields)), 'records.xlsx');
        } else {
            session()->flash('error', 'Nothing to export');
        }
    }

    public function exportPdf($selectedFields)
    {

        // dd($this->selectedFields);
        $this->selectedFields = array_unique(array_merge($this->selectedFields, $selectedFields));

        $this->search(false);
        $records = $this->query->get();
        $selectedFields = $this->selectedFields;
        // $this->resetInput();
        // dd($this->selectedFields);

        $records->map(function ($item, $key) {
            return $item->setHidden($this->processExcludeFields($this->excludedFields));
        });

        //export with excel pdf
        // return Excel::download(new ExportListings($records, $selectedFields, $this->excludedFields), 'records.pdf');

        //export with dompdf
        // $customPaper = array(0, 0, 2000.00, 2000.00);
        // $pdfContent = PDF::loadView('common.export-pdf', compact('records'))->setPaper($customPaper, 'portrait')->output();

        //export with snappy pdf
        $oriantation = 'Portrait';
        if (count($selectedFields) > 8) {
            $oriantation = 'landscape';
        }

        $pdfContent = SnappyPDF::loadView('common.export-pdf', compact('records'))
            ->setPaper('a4')
            ->setOrientation($oriantation)
            ->setOption('margin-bottom', 15)
            ->setOption('title', 'Records')
            ->setOption('enable-smart-shrinking', true)
            ->output();

        return response()->streamDownload(
            fn () => print($pdfContent),
            "records.pdf"
        );
    }

    public function processSelectedFields(): array
    {
        $processedFields = [];
        foreach ($this->selectedFields as $field) {
            $processedFields[] = $field . ' as ' . Str::replace('.', '_', $field);
        }

        return array_unique($processedFields);
    }

    public function processExcludeFields(): array
    {
        $excludeFields = [];
        foreach ($this->excludedFields as $field) {
            if (str_contains($field, '.')) {
                $excludeFields[] = Str::replace('.', '_', $field);
            } else {
                $excludeFields[] = $field;
            }
        }

        return array_unique($excludeFields);
    }
    /*public function referents($type){

    }

    public function professionals($type){

    }

    public function companies($type){

    }

    public function students($type){

    }

    public function projects($type){

    }

    public function courses($type){

    }

    public function requests($type){

    }*/
}
