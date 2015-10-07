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
        return User::with('links')->get();
    }

    public function getById($id)
    {
        return User::with('links', 'entities')->whereId($id)->first();
    }

    public function getByUsername($username)
    {
        return User::with('links', 'entities')->whereUsername($username)->get();
    }



}