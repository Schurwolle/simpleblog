<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;
use App\User;
use App\article;
use App\Comment;

class AuthPolicy
{
    use HandlesAuthorization;


 

    public function userAuth(User $user, User $user1)
    {
        return $user1->id == $user->id;
    }


    public function articleAuth(User $user, article $article)
    {
        return $user->id == $article->user_id;
    }

    public function commentAuth(User $user, Comment $comment)
    {
    	return $user->id == $comment->user_id;
    }
}
