<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //check if logged in user is master
        Blade::if('masteruser', function($value) {

            if (method_exists($value, 'isMaster')) {
                return $value->isMaster() == true;
            }

            return false;
        });
    }
}
