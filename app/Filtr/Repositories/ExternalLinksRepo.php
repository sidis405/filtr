<?php

namespace Filtr\Repositories;

use Filtr\Models\ExternalLinks;

/**
* ExternalLinks Repo
*/
class ExternalLinksRepo
{
    
    public function save(ExternalLinks $external_link)
    {
        $existing = $this->getByUrl($external_link->url);

        if (  $existing ) return $existing;

        $external_link->save();

        return $external_link;
    }

    public function getByUrl($url)
    {
        return ExternalLinks::whereUrl($url)->first();
    }
    
}