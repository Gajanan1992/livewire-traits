<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('can test', function () {
    expect(true)->toBeTrue();
    Artisan::call('livewire-traits:install');
    $this->assertTrue(File::exists(app_path('/traits/' . 'ActiveDeleted.php')));
});
