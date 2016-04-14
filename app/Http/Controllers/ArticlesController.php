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
use Illuminate\Support\Facades\Input;

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
            $addImgs = glob('pictures/'.$article->id.'lb*');
            natsort($addImgs);

    	    return view('articles.show', compact('article', 'comments', 'addImgs'));
        
    }

    public function create()
    {
        $tags = $this->tags->lists();

    	return view('articles.create', compact('tags'));
    }

    public function store(ArticleRequest $request)
    {   
        // $messages = array('imgs_count' => 'Maximum number of additional images is 5.',);
        // $validator = Validator::make($request->file('addImgs'), array('addImgs' => array('imgs_count:addImgs,3')),$messages);
        // if ($validator->fails()) 
        // {
        //     return back()->withErrors($validator);
        // }
        if($this->validateAddImgs(null, $request) != null)
        {
            return $this->validateAddImgs(null, $request);
        }
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
           $addImgs = glob('pictures/'.$article->id.'lb*');
           natsort($addImgs);

    	   return view('articles.edit', compact('article', 'tags', 'addImgs'));
    }

    public function update(article $article, UpdateArticleRequest $request)
    {
        if($request->has('delete'))
        {   
            foreach($request->delete as $name => $deleteImage)
            {
                if (file_exists('pictures/'.$article->id.'lb'.$name))
                {
                    unlink('pictures/'.$article->id.'lb'.$name);
                }
            }
        }

        if($this->validateAddImgs($article, $request) != null)
        {
            return $this->validateAddImgs($article, $request);
        }

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
            $fileName = $article->id;
            $this->upload($mask, $fileName);
        }

        
        $mask = glob('pictures/cropper/croppedthumb'.$userName.'*');
        if(!empty($mask) && $request->thumbnailImage != "")
        {
            $fileName = $article->id.'thumbnail';
            $this->upload($mask, $fileName);
        }

        $mask = glob('pictures/cropper/lightbox2'.$userName);
        if(!empty($mask) && $request->img != "")
        {
            $fileName = $article->id.'lightbox2';
            $this->upload($mask, $fileName);
        }

        if($request->hasFile('addImgs'))
        {
            $files = $request->file('addImgs');
            $mask = glob('pictures/'.$article->id.'lb*');
            natsort($mask);
            $uploadCount = $mask[count($mask)-1];
            $uploadCount = substr($uploadCount, strlen($uploadCount)-1, 1) + 1;

            foreach($files as $file)
            {
                $destinationPath = 'pictures/';
                $fileName = $article->id.'lb'.$uploadCount;
                $file->move($destinationPath, $fileName);
                $uploadCount++;
            }
        }
    }

    private function upload($mask, $fileName)
    {
        $photo = $mask[0];
        $manager = new ImageManager();
        $image = $manager->make($photo)->save('pictures/'.$fileName);
    }

    private function deleteImages(article $article)
    {
        $mask = 'pictures/'.$article->id.'*';
        if (!empty($mask))
        {
            array_map('unlink', glob($mask));
        }
    }

    private function validateAddImgs($article, $request)
    {
        if($request->hasFile('addImgs'))
        {   

            $oldfiles = $article != null ? glob('pictures/'.$article->id.'lb*') : [];
            $files = $request->file('addImgs');
            if(count($files) + count($oldfiles) > 5)
            {
                \Session::flash('alert_message', 'Maximum number of additional images is 5.');
                return back()->withInput();
            }
            foreach($files as $file)
            {
                $rules = array('Additional Image' => 'image|max:2048');
                $validator = Validator::make(array('Additional Image'=> $file), $rules);
                if($validator->fails())
                {
                    return back()->withInput()->withErrors($validator);
                }
            }
        }
    }
}


