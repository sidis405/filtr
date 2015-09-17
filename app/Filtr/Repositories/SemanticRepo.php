<?php

namespace Filtr\Repositories;

use Filtr\Utils\Alchemy\AlchemyAPI;

/**
* Semantic Repo
*/
class SemanticRepo
{
    public $alchemy;

    public function __construct(AlchemyAPI $alchemy)
    {
        $this->alchemy = $alchemy;
    }

    public function getKeywords($url)
    {
        $response = $this->alchemy->entities('url', $url, null);

        return $response['entities'];
    }
    
}