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

        return $comment;
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('commentAuth', $comment);
    		
            $comment->delete();
    }

    public function update(Comment $comment, Request $request)
    {
        $this->authorize('commentAuth', $comment);

            $comment->update($request->all());

            return $comment->body;
    }
}
