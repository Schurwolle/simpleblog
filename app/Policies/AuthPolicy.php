<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;
use App\User;
use App\article;
use App\Comment;
use Carbon\Carbon;

class AuthPolicy
{
    use HandlesAuthorization;


 
    public function adminAuth(User $user)
    {
        return $user->isAdmin();
    }

    public function userAuth(User $user, User $user1)
    {
       if($user1->id == $user->id || $user->isAdmin())
       {
            return true;
       }else
       {
            return false;
       }
    }


    public function articleAuth(User $user, article $article)
    {
        if($user->id == $article->user_id || $user->isAdmin())
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function commentAuth(User $user, Comment $comment)
    {
    	if($user->id == $comment->user_id || $user->isAdmin())
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function unpublishedAuth(User $user, article $article)
    {
        if ($article->published_at > Carbon::now() && $user->id != $article->user_id && (!$user->isAdmin()))
        {
            return false;
        }else
        {
            return true;
        }
    }
}
