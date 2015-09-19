<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Links\CreateLinkRequest;
use Filtr\Repositories\LinksRepo;
use Illuminate\Http\Request;

class LinksController extends Controller
{

    public function __construct()
    {
        // $this->middleware('cachebefore');
        // $this->middleware('cacheafter');
    }

    public function index()
    {
        return view('links.index');
    }

    public function store(CreateLinkRequest $request, LinksRepo $links)
    {

        $link = $links->getBySlug(sluggifyUrl($request->input('url')));

        if ( $link ) return redirect()->to($link->slug);
 

        $new_link = $this->dispatchFrom('App\Commands\Links\CreateLinkCommand', $request);

        return redirect()->to($new_link->slug);

    }

    public function show($slug, LinksRepo $links)
    {
        $link = $links->getBySlug($slug);

        if ( ! $link ) abort(404);

        return view('links.show', compact('link'));
    }

}
