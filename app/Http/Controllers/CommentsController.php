<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Comment;
use App\article;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\CommentRequest;
use Auth;


class CommentsController extends Controller
{

    public function store(CommentRequest $request)
    {
    	$input['user_id'] = $request->user()->id;
    	$input['article_id'] = $request->input('article_id');
    	$input['body'] = preg_replace("/(\r?\n){2,}/", "\n\n", trim($request->input('body')));

    	$comment = Comment::create($input);

        return $comment;
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('commentAuth', $comment);
    		
            $article = $comment->article;
            $comment->delete();
            if($article->hasCommentFromUser(Auth::id()) == true)
            {
                return 'true';
            } 
            return 'false';
    }

    public function update(Comment $comment, CommentRequest $request)
    {
        $this->authorize('commentAuth', $comment);

            $comment->update(['body' => preg_replace("/(\r?\n){2,}/", "\n\n", trim($request->body))]);

            return $comment->body;
    }
}
