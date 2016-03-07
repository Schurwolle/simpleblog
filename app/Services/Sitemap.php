<?php

namespace App\Services;

use Laravelista\Bard\Laravel\Sitemap as Bard;
use App\article;
use App\Tag;
use App\User;

class Sitemap extends Bard {

    /**
     * Use $this->addUrl() to add named route to sitemap.
     * You can also add translations and other properties
     * with the object returned from addUrl() method.
     * You will probably want to add translations.
     *
     * @param $routeName
     * @return mixed
     */
    public function addNamedRoute($routeName)
    {
        $this->addUrl(route($routeName));

    }

    /**
     * Implement your own way for getting localized route url.
     *
     * @param $routeName
     * @param $locale
     * @return mixed
     */
    public function getLocalizedUrlForRouteName($routeName, $locale)
    {
        return LaravelLocalization::getLocalizedURL(
            $locale, parse_url(route($routeName) . '/', PHP_URL_PATH)
        );
    }

    public function addArticles()
	{
	    $articles = article::published()->latest('published_at')->get();
	        
	    foreach($articles as $article)
	    {
	        $this->addUrl(route('articles.show', $article->slug));
	    }
	}	

	public function addTags()
	{
	    $tags = Tag::latest('created_at')->get();
	        
	    foreach($tags as $tag)
	    {
	        $this->addUrl(url('tags/'.$tag->name));
	    }
	}	

	public function addUsers()
	{
	    $users = User::latest('created_at')->get();
	        
	    foreach($users as $user)
	    {
	        $this->addUrl(url($user->name.'/profile'));
	    	$this->addUrl(url($user->name.'/articles'));
	    }
	}	

}