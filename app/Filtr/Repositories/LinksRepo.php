<?php

namespace Filtr\Repositories;

use Filtr\Models\Links;
use Filtr\Repositories\ReadabilityRepo;
use Filtr\Repositories\SemanticRepo;

/**
* Links Repo
*/
class LinksRepo
{
    public $readability;
    public $semantics;
    
    public function __construct(ReadabilityRepo $readability, SemanticRepo $semantics)
    {
        $this->readability = $readability;
        $this->semantics = $semantics;
    }

    public function save(Links $link)
    {
        $link->save();

        return $link;
    }

    public function getLatest($howMany = 15)
    {
        return Links::with('keywords', 'entities.subtypes')->whereStatus(1)->latest()->simplePaginate($howMany);
    }

    public function getBySlug($slug)
    {
        return Links::with('keywords', 'entities.subtypes', 'media')->whereSlug($slug)->first();
    }


    public function getReadability($url)
    {
        $content = $this->readability->filter($url);
        return $content;
    }

    public function getSemantics($content, $entities = [])
    {
        $meta = $this->semantics->extractSemanticData($content, $entities);
        return $meta;
    }
}
