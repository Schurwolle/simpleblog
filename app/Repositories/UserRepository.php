<?php

namespace App\Repositories;

use App\User;

class UserRepository 
{

	public function showAll()
	{
		return User::where('admin', 0)->latest('created_at')->paginate(5);
	}

	public function showSorted()
	{
		$users = User::get();

        $usersSorted = $users->sortByDesc(function ($user, $key){
            return count($user->articles);
        });

        return $usersSorted;
	}

}