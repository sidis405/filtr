<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Filtr\Repositories\FilterRepo;
use Filtr\Repositories\SemanticRepo;
use Illuminate\Http\Request;

class LinksController extends Controller
{

    public function __construct()
    {
        $this->middleware('cachebefore');
        $this->middleware('cacheafter');
    }

    public function index(FilterRepo $filter, SemanticRepo $semantics)
    {
        $data = $this->getFiltrData($filter, $semantics);

        return $data;

        return view('links.index', compact('data'));

    }

    public function getFiltrData(FilterRepo $filter, SemanticRepo $semantics)
    {
        $url = 'http://www.wired.co.uk/magazine/archive/2015/03/start/scaling-dublin-summit';

        $content = $filter->filter($url);

        $meta = $semantics->extractSemanticData($content, ['keywords', 'entities']);

        $data = array_add($meta, 'content' , $content);

        return $data;
    }
}
