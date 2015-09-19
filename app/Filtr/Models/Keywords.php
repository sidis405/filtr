<?php

namespace Filtr\Models;

use Illuminate\Database\Eloquent\Model;

class Keywords extends Model
{
    protected $fillable = ['text', 'slug'];

    public function entities()
    {
        return $this->belongsToMany('Filtr\Models\Entities', 'entity_keyword', 'keyword_id', 'entity_id')->withTimestamps();
    }

    public function links()
    {
        return $this->belongsToMany('Filtr\Models\Links', 'keyword_link', 'keyword_id', 'link_id')->withTimestamps();
    }

    public static function make($text, $slug)
    {   
        $keyword = new static(compact('text', 'slug'));

        return $keyword;
    }
}
