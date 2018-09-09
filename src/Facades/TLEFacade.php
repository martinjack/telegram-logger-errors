<?php namespace TLE\Facades;

use Illuminate\Support\Facades\Facade;

class TLEFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tle';
    }
}
