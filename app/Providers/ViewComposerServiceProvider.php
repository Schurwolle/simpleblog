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
            
            $tags = Tag::get();

            $tagssorted = $tags->sortByDesc(function ($tag, $key){
                return count($tag->articles);
            });


            $users = User::get();

            $userssorted = $users->sortByDesc(function ($user, $key){
                return count($user->articles);
            });

            $view->with('tags', $tagssorted);

            $view->with('users', $userssorted);
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
