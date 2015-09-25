<?php

namespace Filtr\Repositories;

use Filtr\Models\Subtypes;

/**
* Subtypes Repo
*/
class SubtypesRepo
{
    /**
     * Persist subtypes
     * @param  Subtypes $subtype Subtype model
     * @property string $name Name of subtype
     * @return Subtypes           Subtype model
     */
    public function save(Subtypes $subtype)
    {

        $existing = $this->getByName($subtype->name);

        if (  $existing ) return $existing;

        $subtype->save();

        return $subtype;
    }

    public function getByName($name)
    {
        return Subtypes::whereName($name)->first();
    }
    
}