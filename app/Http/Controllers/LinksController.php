<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Links\CreateLinkRequest;
use Filtr\Repositories\LinksRepo;
use Filtr\Repositories\ScratchRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class LinksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['store']]);
        // $this->middleware('cachebefore', ['only' => ['show', 'showAjax']]);
        // $this->middleware('cacheafter', ['only' => ['show', 'showAjax']]);
        parent::__construct();
    }

    public function index(LinksRepo $links_repo)
    {
        $links = $links_repo->getLatest(99);

        return view('links.index', compact('links'));
    }

    public function store(CreateLinkRequest $request, LinksRepo $links)
    {
        $link = $links->getBySlug(sluggifyUrl($request->input('url')));

        if ($link) {
            return redirect()->to($link->slug);
        }
 

        $new_link = $this->dispatchFrom('App\Commands\Links\CreateLinkCommand', $request);

        return redirect()->to($new_link->slug);
    }

    public function show($slug, LinksRepo $links)
    {
        list($link, $related) = $this->getLink($slug, $links);

        $title = $link->title;

        Session::put('current_stream', [$slug]);

        return view('links.show', compact('link', 'related', 'title'));
;
    }

    public function showAjax($slug, LinksRepo $links)
    {
        list($link, $related) = $this->getLink($slug, $links);

        $title = $link->title;

        $current_stream = Session::get('current_stream');

        $current_stream[] = $slug;

        Session::put('current_stream', $current_stream);

        $view = view('links.article', compact('link', 'related', 'title'));
        $sections = $view->renderSections();
        return $view;
    }



    public function getLink($slug, LinksRepo $links)
    {
        $link = $links->getBySlug($slug);

        if (! $link) {
            abort(405);
        }

        $this->incrementReadCounter($link, $slug);

        $relatedByKeywords = $link->relatedByKeywords();
        $relatedByEntities = $link->relatedByEntities();

        $related = mergeRelated($relatedByKeywords, $relatedByEntities);

        return [$link, $related];
    }

    public function incrementReadCounter($link, $slug)
    {
        if( Session::get('last_read_article') !== $slug)
        {
            $link->increment('read_counter');
            
            Session::put('last_read_article', $slug);
        }
    }

    public function seed(Request $request, ScratchRepo $scratch)
    {
        $links = $scratch->seedUrls();

            $this->dispatchFrom('App\Commands\Links\CreateLinkCommand', array_add($request, 'url', $links));

        return 'Done seeding. Await reply.';

    }

    public function parse(LinksRepo $links)
    {
        
        $links->parseContent();
    }

   

}

