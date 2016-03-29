<?php

namespace App\Http\Controllers;


use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\article;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Tag;
use App\User;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\TagRepository;
use Validator;
use Intervention\Image\ImageManager;

class ArticlesController extends Controller
{	
    protected $articles;
    protected $comments;
    protected $tags;


	public function __construct(ArticleRepository $articles,CommentRepository $comments,TagRepository $tags)
	{
		$this->middleware('auth');

        $this->articles = $articles;
        $this->comments = $comments;
        $this->tags     = $tags;
	}



    public function index()
    {	
        $articles = $this->articles->showPublished();

    	return view('articles.headings.articles', compact('articles'));
    }


    public function show(article $article)
    {
        $this->authorize('unpublishedAuth', $article);

            session_start();
            if(!isset($_SESSION['hasVisited'.$article->id]))
            {
                $_SESSION['hasVisited'.$article->id] = "yes";
                $article->visits++;
                $article->save();
            }

            $comments = $this->comments->forArticle($article);

    	    return view('articles.show', compact('article', 'comments'));
        
    }

    public function create()
    {
        $tags = $this->tags->lists();

    	return view('articles.create', compact('tags'));
    }

    public function store(ArticleRequest $request)
    {   
        $article = Auth::user()->articles()->create($request->all());
        $article->slug = str_slug($article->title, '-');
        $article->save();

        $this->syncTags($article, $request);
        $this->uploadImages($article, $request);

    	\Session::flash('flash_message', 'Your article has been created!');

    	return redirect('articles');
    }

    public function edit(article $article)
    {
        $this->authorize('articleAuth', $article);

        
    	   $tags = $this->tags->lists();

    	   return view('articles.edit', compact('article', 'tags'));
    }

    public function update(article $article, UpdateArticleRequest $request)
    {

    	$article->update($request->all());

        $article->slug = str_slug($article->title, '-');
        $article->save();
        
        $this->syncTags($article, $request);

        $this->uploadImages($article, $request);

        \Session::flash('flash_message', 'The article has been updated!');

    	return redirect('articles/'.$article->slug);
    }

    public function destroy(article $article)
    {
        $this->authorize('articleAuth', $article);

            $this->deleteImages($article);

            $article->delete();

            \Session::flash('flash_message', 'The article has been deleted!');

            return redirect('articles');
    }

    public function favorite(article $article)
    {

        if(!$article->favoritedBy->contains(Auth::id()))
        {
            $article->favoritedBy()->attach(Auth::id());

        } else {

            $article->favoritedBy()->detach(Auth::id());
        }
    }


    private function syncTags($article, $request)
    {
        if ( ! $request->has('tag_list'))
        {
            $article->tags()->detach();
            return;
        }

        $allTagIds = array();

        foreach ($request->tag_list as $tagId)
        {
            if (substr($tagId, 0, 4) == 'new:')
            {
                $newTag = Tag::create(['name' => strtolower(substr($tagId, 4))]);
                $allTagIds[] = $newTag->id;
                continue;
            }
            $allTagIds[] = $tagId;
        }

        $article->tags()->sync($allTagIds);
    }

    private function uploadImages(article $article, $request)
    {   
        $userName = Auth::user()->name;

        $mask = glob('pictures/cropper/croppedimg'.$userName.'*');
        if(!empty($mask) && $request->img != "")
        {
            $photo = $mask[0];
            $fileName = $article->id;

            $manager = new ImageManager();
            $image = $manager->make($photo)->save('pictures/'.$fileName);
        }

        
        $mask = glob('pictures/cropper/croppedthumb'.$userName.'*');
        if(!empty($mask) && $request->thumbnailImage != "")
        {
            $photo = $mask[0];
            $fileName = $article->id.'thumbnail';

            $manager = new ImageManager();
            $image = $manager->make($photo)->save('pictures/'.$fileName);
        }
    }

    private function deleteImages(article $article)
    {
        if (file_exists('pictures/'.$article->id))
            {
                unlink('pictures/'.$article->id);
            }

            if (file_exists('pictures/'.$article->id.'thumbnail'))
            {
                unlink('pictures/'.$article->id.'thumbnail');
            }
    }
}


