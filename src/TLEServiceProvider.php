<?php namespace TLE;

use Illuminate\Support\ServiceProvider;

class TLEServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        $configPath = __DIR__ . '/config/tle.php';

        $this->publishes([

            $configPath => config_path(

                'tle.php'

            ),

            'telegram-logger-errors',

        ]);

    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app()->bind('tle', function () {

            return new \TLE\TLESender;

        });

    }

}
