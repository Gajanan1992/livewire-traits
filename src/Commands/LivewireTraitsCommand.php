<?php

namespace gajanan\LivewireTraits\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LivewireTraitsCommand extends Command
{
    public $signature = 'livewire-traits:install';

    public $description = 'Install required livewire traits';

    public function handle(): int
    {
        $this->info('Publishing traits...');

        //check if directory exists
        $directory = app_path() . '/traits';

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        $traitFiles = File::files(__DIR__ . '/../../src/Traits');
        foreach ($traitFiles as $key => $file) {
            File::copy($file, $directory . '/' . $file->getBasename());
        }


        $this->info('Installed traits successfully!');

        return self::SUCCESS;
    }
}
