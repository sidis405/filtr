<?php

namespace Filtr\Repositories;

use Filtr\Models\Subtypes;

/**
* Subtypes Repo
*/
class SubtypesRepo
{
    
    public function save(Subtypes $entity)
    {

        $existing = $this->getByName($entity->name);

        if (  $existing ) return $existing;

        $entity->save();

        return $entity;
    }

    public function getByName($name)
    {
        return Subtypes::whereName($name)->first();
    }
    
}