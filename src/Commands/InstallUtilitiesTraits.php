<?php

namespace gajanan\LivewireTraits\Commands;

use Illuminate\Support\Facades\File;

trait InstallUtilitiesTraits
{

    public function installUtilitiesTraits()
    {
        $this->info('installing utilities traits...');

        $traitFiles = File::files(__DIR__ . '/../../src/stubs/utilities');

        foreach ($traitFiles as $key => $file) {
            File::copy($file, $this->directory . '/' . $file->getBasename());
        }

        $this->info('Installed utilities traits successfully!');
    }
}
