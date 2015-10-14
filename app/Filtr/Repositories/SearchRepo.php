<?php

namespace Filtr\Repositories;

use Filtr\Models\Keywords;
use Filtr\Models\Links;
use Filtr\Models\Titles;
use SearchIndex;

/**
* SearchRepo
*/
class SearchRepo
{

    public function search($string, $highlight_field, $type = 'links')
    {
        $query = $this->makeQuery($string, $highlight_field, $type);

        return $this->getResults($query);
    }

    public function makeQuery($string, $highlight_field, $type = 'links')
    {
        $params = [];
        
        $params['type'] = $type;

        $params['body'] = array(
            'from' => 0,
            'size' => 500,
            'query' => array(
                 'fuzzy_like_this' => array(
                   '_all' => array(
                            'like_text' => $string,
                            'fuzziness' => 0.5,
                        )
                ),
                'highlight' => array(
                        "pre_tags" => ["<result>"],
                        "post_tags" => ["</result>"],
                         'fields' => array(
                             $highlight_field => (object) array() 
                         )
                     )
            ),
        );

        return $params;
    }

    public function getResults($query)
    {
        return SearchIndex::getResults($query);
    }

    public function clearIndex()
    {
        return SearchIndex::clearIndex();
    }

    public function buildIndex()
    {
        $links = Links::get();

        $keywords = Keywords::get();
        
        $titles = Titles::get();

        foreach($links as $link)
        {
           SearchIndex::upsertToIndex($link);
        }

        foreach($keywords as $keyword)
        {
           SearchIndex::upsertToIndex($keyword);
        }

        foreach($titles as $title)
        {
           SearchIndex::upsertToIndex($title);
        }

        return 'indexed all links and keywords';

    }

}
