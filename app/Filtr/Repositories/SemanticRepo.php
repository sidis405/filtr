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

    public function extractSemanticData($resource,  $entities = [], $type = 'html')
    {
        $data = [];

        foreach ($entities as $entity) {
            $fname = 'get' . ucfirst($entity);
            $data[$entity] = $this->$fname($type, $resource);
        }

        return $data;
    }

    public function getKeywords($type, $url)
    {
        $response = $this->alchemy->keywords($type, $url, null);

        return $response['keywords'];
    }

    public function getEntities($type, $url)
    {
        $response = $this->alchemy->entities($type, $url, null);

        return $response['entities'];
    }
    
}
