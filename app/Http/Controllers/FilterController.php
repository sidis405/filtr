<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Filtr\Repositories\FilterRepo;
use Filtr\Repositories\TermExtractionRepo;
use Filtr\Repositories\SemanticRepo;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function index(FilterRepo $filter, TermExtractionRepo $extractor, SemanticRepo $semantics)
    {
        $url = 'http://www.wired.co.uk/magazine/archive/2015/03/start/scaling-dublin-summit';

        // $data = $filter->filter($url);

        // return $extractor->extract($data);
        // 
        return $semantics->getKeywords($url);

        return view('index', compact('data'));
    }
}
