<?php

namespace Filtr\Models;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{

    protected $fillable = ['url', 'content', 'user_id', 'slug', 'domain', 'hash'];

    public function user()
    {
        return $this->belongsTo('Filtr\Models\User');
    }

    public function entities()
    {
        return $this->belongsToMany('Filtr\Models\Entities', 'entity_link', 'link_id', 'entity_id')->withTimestamps();
    }

    public function keywords()
    {
        return $this->belongsToMany('Filtr\Models\Keywords', 'keyword_link', 'link_id', 'keyword_id')->withTimestamps();
    }

    public static function make($url, $content, $user_id, $slug, $domain, $hash)
    {   
        $staff = new static(compact('url', 'content', 'user_id', 'slug', 'domain', 'hash'));

        return $staff;
    }
}
