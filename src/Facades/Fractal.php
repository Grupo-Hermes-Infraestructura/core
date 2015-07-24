<?php

namespace Ghi\Core\Facades;

use Illuminate\Support\Facades\Facade;

class Fractal extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return 'fractal.manager';
    }
}
