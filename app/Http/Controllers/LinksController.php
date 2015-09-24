<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Links\CreateLinkRequest;
use Filtr\Models\Links;
use Filtr\Repositories\LinksRepo;
use Illuminate\Http\Request;

class LinksController extends Controller
{

    public function __construct()
    {
        // $this->middleware('cachebefore');
        // $this->middleware('cacheafter');
        $this->middleware('auth', ['only' => ['store']]);
        parent::__construct();
    }

    public function index(LinksRepo $links_repo)
    {
        $links = $links_repo->getLatest(15);

        return view('links.index', compact('links'));
    }

    public function store(CreateLinkRequest $request, LinksRepo $links)
    {

        $link = $links->getBySlug(sluggifyUrl($request->input('url')));

        if ( $link ) return redirect()->to($link->slug);
 

        $new_link = $this->dispatchFrom('App\Commands\Links\CreateLinkCommand', $request);

        return redirect()->to($new_link->slug);
        // return view('links.debug');

    }

    public function show($slug, LinksRepo $links)
    {
        $link = $links->getBySlug($slug);

        if ( ! $link ) abort(404);

        // return $link;

        $relatedByKeywords = $link->relatedByKeywords();
        $relatedByEntities = $link->relatedByEntities();

        $related = mergeRelated($relatedByKeywords, $relatedByEntities);

        return view('links.show', compact('link', 'related'));
    }
}
