<?php

namespace Filtr\Repositories;

use Filtr\Models\Keywords;

/**
* Keywords Repo
*/
class KeywordsRepo
{
    /**
     * Persist an keyword
     * @param  \Filtr\Models\Keywords $keyword keyword model
     * @property string $slug Slug
     * @return \Filtr\Models\Keywords           keyword model
     */
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