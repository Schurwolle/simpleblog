<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\article;
use App\Tag;
use App\User;

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

        view()->composer('leftandright', function ($view)
        {
            $view->with('tags', Tag::latest('created_at')->get());

            $view->with('users', User::get());
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
