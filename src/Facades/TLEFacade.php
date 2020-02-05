<?php

namespace TLE\Facades;

use Illuminate\Support\Facades\Facade;

class TLEFacade extends Facade
{
    /**
     *
     * getFacadeAccessor
     *
     * @return STRING
     *
     */
    protected static function getFacadeAccessor()
    {
        return 'tle';
    }
}
