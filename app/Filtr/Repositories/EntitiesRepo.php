<?php

namespace Filtr\Repositories;

use Filtr\Models\Entities;

/**
* Entities Repo
*/
class EntitiesRepo
{
    /**
     * Persist an entity
     * @param  Filtr\Models\Entities $entity Entity model
     * @property string $slug Slug
     * @return Filtr\Models\Entities           Entity model
     */
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