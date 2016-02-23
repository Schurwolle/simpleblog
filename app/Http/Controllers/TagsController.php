<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tag;
use Carbon\Carbon;

class TagsController extends Controller
{
    public function show(Tag $tag)
    {
    	$articles = $tag->articles()->latest('published_at')->published()->paginate(5);

    	return view('articles.tags', compact('articles', 'tag'));
    }
}
