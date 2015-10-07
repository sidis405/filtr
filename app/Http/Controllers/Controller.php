<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $isSignedIn;
    public $user;

    public function __construct() {
        $this->user = $this->isSignedIn = \Auth::user();
        view()->share('currentUser', $this->user);
        view()->share('title', 'Filtr');
        view()->share('isSignedIn', $this->isSignedIn);

    }
}
