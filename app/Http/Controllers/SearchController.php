<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Filtr\Models\Links;
use Illuminate\Http\Request;
use SearchIndex;

class SearchController extends Controller
{

    public function makeElasticIndex()
    {
        $links = Links::get();

        foreach($links as $link)
        {
           SearchIndex::upsertToIndex($link);
        }

        return 'indexed all links';
    }

    public function search(Request $request)
    {
        if(strlen($request->input('q')) > 3)
        {
            $query =
                [
                    'body' =>
                        [
                            'from' => 0,
                            'size' => 500,
                            'query' =>
                                [
                                    'fuzzy_like_this' =>
                                        [
                                            '_all' =>
                                                [
                                                    'like_text' => $request->input('q'),
                                                    'fuzziness' => 0.5,
                                                ],
                                        ],
                                ],
                        ]
                ];
                $results = SearchIndex::getResults($query);

                return $results['hits']['hits'];

        } else {

            return view('search.create');
        }
            
    }


}
