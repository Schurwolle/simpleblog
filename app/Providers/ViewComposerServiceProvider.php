<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\article;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.app', function ($view)
        {
            $view->with('latest', article::latest('published_at')->published()->first());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
