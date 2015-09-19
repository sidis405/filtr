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
    
    function __construct(ReadabilityRepo $readability, SemanticRepo $semantics)
    {
        $this->readability = $readability;
        $this->semantics = $semantics;
    }

    public function save(Links $link)
    {
        $link->save();

        return $link;
    }

    public function getBySlug($slug)
    {
        return Links::with('keywords', 'entities')->whereSlug($slug)->first();
    }

    public function getLinksData($url, $entities = [])
    {
        $content = $this->readability->filter($url);

        $meta = $this->semantics->extractSemanticData($content, $entities);

        $data = array_add($meta, 'content' , $content);

        return $data;
    }
}