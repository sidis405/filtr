<?php

namespace Filtr\Repositories;


/**
* EmbedsRepo
*/
class EmbedsRepo
{
    
    public function fetch($url)
    {
        $content = [];

        $info = \Embed\Embed::create($url);


        $content['title'] = $info->title;
        $content['description'] = $info->description;

        $content['images'] = $info->images;
        $content['image'] = $info->image;

        $content['code'] = $info->code;
        
        $content['author_name'] = $info->authorName;

        $content['publishedDate'] = $info->publishedDate;

        return $content;

    }
}
