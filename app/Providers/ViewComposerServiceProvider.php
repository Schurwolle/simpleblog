<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\ArticleRepository;

class ViewComposerServiceProvider extends ServiceProvider
{

    protected $tags;
    protected $users;
    protected $articles;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(TagRepository $tags, UserRepository $users, ArticleRepository $articles)
    {
        $this->tags     = $tags;
        $this->users    = $users;
        $this->articles = $articles;

        view()->composer('layouts.app', function ($view)
        {
            $view->with('latest', $this->articles->showLatest());

        });

        view()->composer('leftandright', function ($view)
        {
            $view->with('tags', $this->tags->showSorted());

            $view->with('users', $this->users->showSorted());

            $view->with('articles', $this->articles->showSorted());
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
