<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Filtr\Models\User;
use Filtr\Repositories\UsersRepo;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function __construct() {

        $this->middleware('auth', ['except' => ['index', 'show']]);

        parent::__construct();
    
    }

    public function index(UsersRepo $users_repo)
    {
        $users = $users_repo->getAll();

        return $users;
    }

    public function show($id, UsersRepo $users_repo)
    {
        $user = $users_repo->getById($id);

        return $user;

        return view('users.show', compact('user'));

    }


    public function addUserEntities($id, Request $request)
    {
        \Auth::user()->entities()->attach([$request->input('entity_id')]);
        return 'true';
    }

    public function deleteUserEntities($id, Request $request)
    {
        \Auth::user()->entities()->detach([$request->input('entity_id')]);
        return 'true';
    }

}
