<?php

namespace gajanan\LivewireTraits\Commands;

use Illuminate\Support\Facades\File;

trait InstallExportTraits
{
    public function installExportTraits()
    {
        $this->info('installing export utilities traits...');
        // $this->requireComposerPackages('maatwebsite/excel: ^3.1', 'barryvdh/laravel-snappy: ^0.4.8', 'wemersonjanuario/wkhtmltopdf-windows: 0.12.2.3');
        $this->requireComposerPackages('inertiajs/inertia-laravel:^0.5.4', 'laravel/sanctum:^2.8', 'tightenco/ziggy:^1.0');

        $exportDirectory = app_path('/Exports');
        if (! File::isDirectory($exportDirectory)) {
            File::makeDirectory($exportDirectory, 0755, true, true);
        }

        $traitFiles = File::files(__DIR__ . '/../../src/stubs/export/traits');

        foreach ($traitFiles as $key => $file) {
            File::copy($file, $this->directory . '/' . $file->getBasename());
        }

        // copy file from src/stubs/export/Export to app/Exports/
        File::copy(__DIR__ . '/../../src/stubs/export/Export/ExportListing.php', $exportDirectory . '/' . 'ExportListing.php');

        $this->info('Installed export utilities traits successfully!');
    }
}
