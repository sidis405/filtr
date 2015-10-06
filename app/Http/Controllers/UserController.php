<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Filtr\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function __construct() {

        $this->middleware('auth');
    
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
