<?php

namespace Filtr\Repositories;

use Filtr\Models\DomainBlacklist;
use Filtr\Models\ExternalLinks;

/**
* ExternalLinks Repo
*/
class ExternalLinksRepo
{

    public function getBlackList()
    {
        return DomainBlacklist::all();
    }

    /**
     * Persist external links
     * @param  ExternalLinks $external_link External link model
     * @property string $url Url of external link
     * @return \Filtr\Models\ExternalLinks                      External link model
     */
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

    public function getAll($onlyUnprocessed = false)
    {
        if($onlyUnprocessed){
            return ExternalLinks::with('link')->where('valid', 1)->where('processed', 0)->get();
        }

        return ExternalLinks::with('link')->where('valid', 1)->orderBy('processed', 'DESC')->get();
    }
    
}
