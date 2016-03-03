<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\RssFeed;

class RssController extends Controller
{
  

    public function rss(RssFeed $feed)
	{
	    $rss = $feed->getRSS();

	    return response($rss)
	      ->header('Content-type', 'application/rss+xml');
	}
}
