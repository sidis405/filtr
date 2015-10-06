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

    public function entities($id, Request $request)
    {
        \Auth::user()->entities()->sync([$request->input('entity_id')]);
        return 'true';
    }

}
