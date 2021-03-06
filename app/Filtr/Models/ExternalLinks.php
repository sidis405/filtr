<?php

namespace Filtr\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalLinks extends Model
{

    protected $fillable = ['link_id', 'url', 'slug', 'processed', 'valid'];

    protected $table = 'external_links';


    public function link()
    {
        return $this->belongsTo('Filtr\Models\Links', 'link_id');
    }

    public static function make($link_id, $url, $valid = 1, $processed = 0)
    {   
        $external_link = new static(compact('link_id', 'url', 'valid', 'processed'));

        return $external_link;
    }
}
