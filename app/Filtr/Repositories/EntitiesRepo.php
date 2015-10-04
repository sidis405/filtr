<?php

namespace Filtr\Repositories;

use Filtr\Models\Entities;
use Spatie\Browsershot\Browsershot;

/**
* Entities Repo
*/
class EntitiesRepo
{
    /**
     * Persist an entity
     * @param  Filtr\Models\Entities $entity Entity model
     * @property string $slug Slug
     * @return \Filtr\Models\Entities           Entity model
     */
    public function save(Entities $entity)
    {
        $existing = $this->getBySlug($entity->slug);

        if (  $existing ) return $existing;

        $entity->save();

        return $entity;
    }

    public function makeScreenShots()
    {
        $entities = Entities::whereNull('screenshot')->whereNotNull('website')->get();


        foreach($entities as $entity)
        {
            if(filter_var($entity->website, FILTER_VALIDATE_URL))
            {
                $screenshot = $this->takeScreenshowOfWebsite($entity->website);
                $entity->update(['screenshot' => $screenshot]);
            }
        }

        return $entities;
    }

    public function takeScreenshowOfWebsite($url)
    {
        // dd(public_path().'/screenshots/'. str_slug($url) . '.jpg');
        $browsershot = new Browsershot();
        $browsershot
            ->setURL($url)
            ->setWidth('1024')
            ->setHeight('768')
            ->save(public_path().'/screenshots/'. str_slug($url) . '.jpg');

            return '/screenshots/'. str_slug($url)  . '.jpg';
    }

    public function getBySlug($slug)
    {
        return Entities::with('links', 'subtypes')->whereSlug($slug)->first();
    }
    
}
