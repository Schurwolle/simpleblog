<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\article;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;

class ViewComposerServiceProvider extends ServiceProvider
{

    protected $tags;
    protected $users;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(TagRepository $tags, UserRepository $users)
    {
        $this->tags  = $tags;
        $this->users = $users;

        view()->composer('layouts.app', function ($view)
        {
            $view->with('latest', article::latest('published_at')->published()->first());

        });

        view()->composer('leftandright', function ($view)
        {

            $tagsSorted  = $this->tags->showSorted();

            $usersSorted = $this->users->showSorted();

            $articles = article::latest('published_at')->published()->get();

            $view->with('tags', $tagsSorted);

            $view->with('users', $usersSorted);

            $view->with('articles', $articles);
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
