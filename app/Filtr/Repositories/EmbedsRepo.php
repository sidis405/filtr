<?php

namespace Filtr\Repositories;

use Embed\Adapters\AdapterInterface;

/**
* EmbedsRepo
*/
class EmbedsRepo
{
    
    public function fetch($url)
    {
        $content = [];

        $info = \Embed\Embed::create($url);

        if($info instanceof AdapterInterface)
        {

        $content['title'] = $info->title;
        $content['description'] = $info->description;

        $content['images'] = $info->images;
        $content['image'] = $info->image;

        $content['code'] = $info->code;

        $content['publishedDate'] = $info->publishedDate;

        return $content;

        }else{
            return null;
        }
    }
}