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

    	$comment = Comment::create($input);

    	\Session::flash('flash_message', 'Your comment has been published!');
    	return redirect('articles/'.$comment->article->slug);
    }

    public function delete(article $article, Comment $comment)
    {

        $this->authorize('commentAuth', $comment);
    		
        	$articleslug = $comment->article->slug;
            $comment->delete();
            \Session::flash('flash_message', 'The comment has been deleted!');
        

        return redirect('articles/'.$articleslug);

    }

    public function edit(Comment $comment)
    {
        $this->authorize('commentAuth', $comment);

            return view('editcomment', compact('article', 'comment'));

    }

    public function update(Comment $comment, Request $request)
    {
        $this->authorize('commentAuth', $comment);

            $comment->update($request->all());

            \Session::flash('flash_message', 'The comment has been updated!');
            return redirect('articles/'.$comment->article->slug);
    }
}
