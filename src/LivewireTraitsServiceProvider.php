<?php

namespace gajanan\LivewireTraits;

use gajanan\LivewireTraits\Commands\LivewireTraitsCommand;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;

class LivewireTraitsServiceProvider extends ServiceProvider
{
    // public function configurePackage(Package $package): void
    // {
    //     /*
    //      * This class is a Package Service Provider
    //      *
    //      * More info: https://github.com/spatie/laravel-package-tools
    //      */
    //     $package
    //         ->name('livewire-traits')
    //         ->hasConfigFile()
    //         ->hasViews()
    //         ->hasMigration('create_livewire-traits_table')
    //         ->hasCommand(LivewireTraitsCommand::class);
    // }

    //register the package
    public function register()
    {
        //register the package
        // $this->app->register(Package::class);
    }

    public function boot()
    {
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                LivewireTraitsCommand::class,
            ]);
        }
    }
}
