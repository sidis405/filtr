<?php

namespace Filtr\Repositories;

use Filtr\Models\Entities;

/**
* Entities Repo
*/
class EntitiesRepo
{
    
    public function save(Entities $entity)
    {

        $existing = $this->getBySlug($entity->slug);

        if (  $existing ) return $existing;

        $entity->save();

        return $entity;
    }

    public function getBySlug($slug)
    {
        return Entities::whereSlug($slug)->first();
    }
    
}