<?php

namespace gajanan\LivewireTraits\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \gajanan\LivewireTraits\LivewireTraits
 */
class LivewireTraits extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'livewire-traits';
    }
}
