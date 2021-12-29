<?php

namespace PyrobyteWeb\Sms;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('sms', fn() => new Sms());

        $this->publishes([
            __DIR__ . '/../config/sms.php' => $this->app->configPath() . '/sms.php',
        ], 'config');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
