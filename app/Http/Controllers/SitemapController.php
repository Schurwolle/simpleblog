<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Sitemap;


class SitemapController extends Controller
{
    protected $sitemap;

    public function __construct(Sitemap $sitemap)
    {
        $this->sitemap = $sitemap;
    }

	
	public function generate()
	{
		$this->sitemap->addNamedRoutes(['articles.index','articles.create']);

		$this->sitemap->addArticles();
		$this->sitemap->addTags();
		$this->sitemap->addUsers();

        return $this->sitemap->render();
        
	}
}
