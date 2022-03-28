<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('check if export file is istalled', function () {
    $this->assertFalse(File::exists(app_path('/Exports/' . 'ExportListing.php')));
    Artisan::call('livewire-traits:install --export');
    $this->assertTrue(File::exists(app_path('/Exports/' . 'ExportListing.php')));

    //clean up
    unlink(app_path('/Exports/' . 'ExportListing.php'));
});
