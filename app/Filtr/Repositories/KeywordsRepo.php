<?php

namespace Filtr\Repositories;

use Filtr\Models\Keywords;

/**
* Keywords Repo
*/
class KeywordsRepo
{
    
    public function save(Keywords $keyword)
    {

        $existing = $this->getBySlug($keyword->slug);

        if (  $existing ) return $existing;

        $keyword->save();

        return $keyword;
    }

    public function getBySlug($slug)
    {
        return Keywords::whereSlug($slug)->first();
    }
    
}