<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('file found', function () {
    expect(true)->toBeTrue();
    Artisan::call('livewire-traits:install');
    $this->assertTrue(File::exists(app_path('/traits/' . 'Sortable.php')));
});
