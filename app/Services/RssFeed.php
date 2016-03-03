<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use App\article;


class RssFeed
{

	public function getRSS()
	{
	    if (Cache::has('rss-feed')) {
	      return Cache::get('rss-feed');
	    }

	    $rss = $this->buildRssData();
	    Cache::add('rss-feed', $rss, 120);

	    return $rss;
	}


	protected function buildRssData()
	{

	    $now = Carbon::now();
	    $feed = new Feed();
	    $channel = new Channel();
	    $channel
	      ->title('Blog')
	      ->description('My Blog')
	      ->url(url('/'))
	      ->language('en')
	      ->copyright('Copyright (c), Blogger')
	      ->lastBuildDate($now->timestamp)
	      ->appendTo($feed);

	    $articles = article::latest('published_at')
	      ->published()
	      ->take(5)
	      ->get();

	    foreach ($articles as $article) {
	      $item = new Item();
	      $item
	        ->title($article->title)
	        ->description($article->body)
	        ->url(url('/articles/'.$article->slug))
	        ->pubDate($article->published_at)
	        ->guid(url('/articles/'.$article->slug), true)
	        ->appendTo($channel);
	    }


	    return $feed;
  	}
}