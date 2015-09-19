<?php

namespace Filtr\Models;

use Illuminate\Database\Eloquent\Model;

class Entities extends Model
{
    protected $fillable = ['type', 'text', 'slug', 'name', 'website', 'geo'];
    public function keywords()
    {
        return $this->belongsToMany('Filtr\Models\Keywords', 'entity_keyword', 'entity_id', 'keyword_id')->withTimestamps();
    }

    public function links()
    {
        return $this->belongsToMany('Filtr\Models\Keywords', 'entity_link', 'entity_id', 'link_id')->withTimestamps();
    }

    public function subtypes()
    {
        return $this->belongsToMany('Filtr\Models\Subtypes', 'entity_subtype', 'entity_id', 'subtype_id')->withTimestamps();
    }

    public static function make($type, $text, $slug, $name = null, $website = null, $geo = null)
    {   
        $keyword = new static(compact('type', 'text', 'slug', 'name', 'website', 'geo'));

        return $keyword;
    }
}
