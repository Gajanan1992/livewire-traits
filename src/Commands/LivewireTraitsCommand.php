<?php

namespace gajanan\LivewireTraits\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class LivewireTraitsCommand extends Command
{
    use InstallUtilitiesTraits;
    use InstallExportTraits;

    protected $directory;

    //constructor
    public function __construct()
    {
        parent::__construct();
        $this->directory = app_path('/traits');
    }

    public $signature = 'livewire-traits:install {--utilities : it will install the listing related utilities traits}
                                                {--export : it will install the export related utilities traits}';

    public $description = 'Install required livewire traits';

    public function handle()
    {
        $this->info('Publishing traits...');

        //check if directory exists
        if (!File::isDirectory($this->directory)) {
            File::makeDirectory($this->directory, 0755, true, true);
        }

        if ($this->option('export')) {
            $this->installExportTraits();
        } else {
            $this->installUtilitiesTraits();
        }
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param  mixed  $packages
     * @return void
     */
    protected function requireComposerPackages($packages)
    {
        $composer = $this->option('export');

        // if ($composer !== 'global') {
        //     $command = ['php', $composer, 'require'];
        // }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }
}
