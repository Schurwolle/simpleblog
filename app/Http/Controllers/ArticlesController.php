<?php

namespace App\Http\Controllers;


use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\article;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Tag;
use App\User;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\TagRepository;
use Validator;
use Intervention\Image\ImageManager;
use App\Jobs\Delete;
use App\Jobs\DeleteCKEImages;

class ArticlesController extends Controller
{	
    protected $articles;
    protected $comments;
    protected $tags;


	public function __construct(ArticleRepository $articles,CommentRepository $comments,TagRepository $tags)
	{
		$this->middleware('auth', ['except' => 'unique']);

        $this->articles = $articles;
        $this->comments = $comments;
        $this->tags     = $tags;
	}



    public function index()
    {	
        if (session()->has('article'))
        {
            $articles = $this->articles->showExcept(session('article'));
        } else {
            $articles = $this->articles->showPublished();
        }

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
            if(session()->has('article'))
            {
                $article = session('article');
                $query = session('query');
                $query_words = session('query_words');
                $comments = $article->comments;
            }
    	    return view('articles.show', compact('article', 'comments', 'addImgs', 'query', 'query_words'));
        
    }

    public function create()
    {
        $tags = $this->tags->lists();

    	return view('articles.create', compact('tags'));
    }

    public function store(ArticleRequest $request)
    {   
        $article = Auth::user()->articles()->create($request->all());
        $this->syncTags($article, $request);
        $this->uploadImages($article, $request);
        $this->saveCKEImages($article);

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
        $updated = $article->updated_at;

        if($request->has('delete'))
        {   
            foreach($request->delete as $name => $deleteImage)
            {
                if (file_exists('pictures/'.$article->id.'lb'.$name))
                {
                    unlink('pictures/'.$article->id.'lb'.$name);
                }
            }
            $updated = 'true';
        }
        if($request->hasFile('addImgs'))
        {   
            $inputs = array('Additional Images' => $request->file('addImgs'));
            $rules  = array('Additional Images' => 'maximgs:'.$article->id);
            $validator = Validator::make($inputs, $rules);
            if($validator->fails())
            {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            $updated = 'true';
        }   
        
        $article->update($request->all());
        $this->syncTags($article, $request, $updated);
        $this->uploadImages($article, $request, $updated);
        $this->saveCKEImages($article);
        if ($updated != $article->updated_at)
        {
            \Session::flash('flash_message', 'The article has been updated!');
        }
    	return redirect('articles/'.$article->slug);
    }

    public function destroy(article $article)
    {
        $this->authorize('articleAuth', $article);

            $job = (new Delete($article, $article->id));
            $this->dispatch($job);
            
            \Session::flash('flash_message', 'The article has been deleted!');

            return redirect('articles')->with('article', $article->id);
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

    public function unique(Request $request)
    {   
        if($request->table == 'article')
        {
            $row = article::where($request->column, '=', $request->value)
                                ->where($request->column, '!=', $request->oldValue)
                                ->first();
        } elseif($request->table == 'user') {
            $row = User::where($request->column, '=', $request->value)->first();
        }

        if($row == null)
        {
            return 'true';
        } else {
            return 'false';
        }
    }
    public function validateCKEImages(Request $request)
    {
        $inputs = array('body' => $request->body);
        $rules  = array('body' => 'ckeimgs');
        $validator = Validator::make($inputs, $rules);
        if($validator->fails())
        {
            return false;
        }
        return true;
    }


    private function syncTags($article, $request, &$updated = null)
    {
        $tags = $article->tags()->get();

        if (!$request->has('tag_list'))
        {
            $article->tags()->detach();
        } else {

            $allTagIds = array();

            foreach ($request->tag_list as $tagId)
            {
                if (substr($tagId, 0, 3) == 'new')
                {
                    $newTag = Tag::create(['name' => strtolower(substr($tagId, 3))]);
                    $allTagIds[] = $newTag->id;
                    continue;
                }
                $allTagIds[] = $tagId;
            }
            $article->tags()->sync($allTagIds);
        }
        if ($tags != $article->tags()->get())
        {
            $updated = 'true';
        }
    }

    private function uploadImages(article $article, $request, &$updated = null)
    {   
        $userName = Auth::user()->name;

        $mask = glob('pictures/cropper/croppedimg'.$userName.'*');
        if(!empty($mask) && $request->img != "")
        {
            $fileName = $article->id;
            $this->upload($mask, $fileName, $updated);
        }

        $mask = glob('pictures/cropper/croppedthumb'.$userName.'*');
        if(!empty($mask) && $request->thumbnailImage != "")
        {
            $fileName = $article->id.'thumbnail';
            $this->upload($mask, $fileName, $updated);
        }

        $mask = glob('pictures/cropper/lightbox2'.$userName);
        if(!empty($mask) && $request->img != "")
        {
            $fileName = $article->id.'lightbox2';
            $this->upload($mask, $fileName, $updated);
        }

        if($request->hasFile('addImgs'))
        {
            $files = $request->file('addImgs');
            $mask = glob('pictures/'.$article->id.'lb*');
            if(empty($mask))
            {
                $uploadCount = 0;
            } else {
                natsort($mask);
                $mask = array_values($mask);
                $uploadCount = explode('lb', $mask[count($mask)-1])[1] + 1;
            }
            
            foreach($files as $file)
            {
                $destinationPath = 'pictures/';
                $fileName = $article->id.'lb'.$uploadCount;
                $file->move($destinationPath, $fileName);
                $uploadCount++;
            }
        }
    }

    private function upload($mask, $fileName, &$updated)
    {
        $photo = $mask[0];
        $manager = new ImageManager();
        $image = $manager->make($photo)->save('pictures/'.$fileName);
        $updated = 'true';
    }

    private function saveCKEImages($article)
    {
        $old_imgs = glob('pictures/'.$article->id.'CKE*');
        $job = (new DeleteCKEImages($old_imgs, $article->body));
        $this->dispatch($job);
        if(preg_match_all('#<a href="[^<>"]*"[^<>]*><img [^<>]*src="[^<>"]*"[^<>]*/></a>#', $article->body, $matches))
        {
            natsort($old_imgs);
            $num = $old_imgs[count($old_imgs) - 2];
            $num = substr($num, -1) + 1;
            for ($i = 0; $i < count($matches[0]); $i++)
            {                
                $photo = substr($matches[0][$i], 9, strpos($matches[0][$i], '"', 9) - 9);
                preg_match('#src="(.*?)"#', $matches[0][$i], $thumb);

                $manager = new ImageManager();
                $newlink = $matches[0][$i];
                
                if(!starts_with($photo, '/pictures/'))
                {
                    $img_num = starts_with($thumb[1], '/pictures/') ? substr($thumb[1], -6, 1) : $num;
                    $image = $manager->make($photo)->save('pictures/'.$article->id."CKE".$img_num);
                    $photo = $img_num;
                    $newlink = preg_replace('#<a href="(.*?)"#', '<a href="/pictures/'.$article->id.'CKE'.$img_num.'"', $newlink);
                }
                if(!starts_with($thumb[1], '/pictures/'))
                {
                    $thumb_num = $photo == $num ? $num : substr($photo, -1);
                    $imageThumb = $manager->make($thumb[1])->save('pictures/'.$article->id."CKE".$thumb_num."thumb");
                    $newlink = preg_replace('#src="(.*?)"#', 'src="/pictures/'.$article->id.'CKE'.$thumb_num.'thumb"', $newlink);
                }

                $article->body = str_replace($matches[0][$i], $newlink, $article->body);
                $article->save();
                $num++;
            }
        }
    }
}


