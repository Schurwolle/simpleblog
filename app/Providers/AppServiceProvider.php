<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('maximgs', 'App\Validators\CustomValidator@validateMaxImgs');
        Validator::extend('ckeimgs', 'App\Validators\CustomValidator@validateCKEImgs');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
