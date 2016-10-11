<?php

namespace Pbmedia\Specifications\Laravel;

use Illuminate\Support\Facades\Facade;

class SpecificationsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-specifications-matcher';
    }
}
