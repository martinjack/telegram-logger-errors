<?php namespace TLE\Facades;

/**
 *
 * Class TLEFacade
 *
 * @package TLE\Facades
 *
 * @license MIT
 *
 */
use Illuminate\Support\Facades\Facade;

class TLEFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tle';
    }
}
