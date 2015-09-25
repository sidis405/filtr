<?php

namespace Filtr\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @param string $name Name
 */
class Subtypes extends Model
{
    protected $fillable = ['name'];


    public function entities()
    {
        return $this->belongsToMany('Filtr\Models\Entities', 'entity_subtype', 'subtype_id', 'entity_id')->withTimestamps();
    }

    public static function make($name)
    {
        $subtype = new static(compact('name'));

        return $subtype;
    }
}
