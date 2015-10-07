<?php

namespace Filtr\Repositories;

use Filtr\Models\User;


/**
* Users Repo
*/
class UsersRepo
{
    
    public function getAll()
    {
        return User::with('linksCount')->get();
    }

    public function getById($id)
    {
        return User::with('linksCount', 'entities')->whereId($id)->first();
    }

    public function getByUsername($username)
    {
        return User::with('linksCount', 'entities')->whereUsername($username)->get();
    }



}