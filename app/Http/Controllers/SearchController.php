<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Filtr\Models\Links;
use Filtr\Repositories\SearchRepo;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function makeElasticIndex(SearchRepo $search)
    {
        return $search->buildIndex();
    }

    public function searchLinks(Request $request, SearchRepo $search)
    {
        if(strlen($request->input('q')) > 2)
        {
            $query = $request->input('q');

            $results = $search->search($query,  'title', 'links');

            $time = $results['took'];

            $count = $results['hits']['total'];

            $results = $results['hits']['hits'];

            // return $results;

            return view('search.show', compact('query', 'results', 'time', 'count'));

        } else {

            return view('search.create');
        }
    }

    public function searchTitlesKeywords(Request $request, SearchRepo $search)
    {
        if(strlen($request->input('q')) > 2)
        {
            $titles_results = $search->search($request->input('q'), 'title', 'titles');
            $keywords_results = $search->search($request->input('q'), 'text', 'keywords');

            $titles = $titles_results['hits']['hits'];
            $keywords = $keywords_results['hits']['hits'];

            return array_merge($titles, $keywords);

        } else {

            return view('search.create');
        }
    }

    public function destroy(SearchRepo $search)
    {
        $search->clearIndex();

        return redirect()->to('/');
    }

}
