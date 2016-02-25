<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Comment;
use App\article;
use Illuminate\Support\Facades\Input;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
    	$input['user_id'] = $request->user()->id;
    	$input['article_id'] = $request->input('article_id');
    	$input['body'] = $request->input('body');

    	Comment::create($input);

    	\Session::flash('flash_message', 'Your comment has been published!');
    	return redirect('articles/'.$input['article_id']);
    }

    public function delete(article $article, Comment $comment)
    {

        $this->authorize('commentAuth', $comment);
    	// if(Auth::id() == $comment->user_id)
     //    {	
        	$articleid = $comment->article_id;
            $comment->delete();
            \Session::flash('flash_message', 'Your comment has been deleted!');
        // }

        return redirect('articles/'.$articleid);

    	
    }
}
