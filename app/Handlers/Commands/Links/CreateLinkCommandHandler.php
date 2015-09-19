<?php

namespace App\Handlers\Commands\Links;

use App\Commands\Links\CreateLinkCommand;
use App\Events\Links\LinkWasCreated;
use Event;
use Filtr\Models\Entities;
use Filtr\Models\Keywords;
use Filtr\Models\Links;
use Filtr\Models\Subtypes;
use Filtr\Repositories\EntitiesRepo;
use Filtr\Repositories\KeywordsRepo;
use Filtr\Repositories\LinksRepo;
use Filtr\Repositories\SubtypesRepo;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;

class CreateLinkCommandHandler
{
    public $links;
    public $keywords;
    public $entities;
    public $subtypes;

    /**
     * Create the command handler.
     *
     * @return void
     */
    public function __construct(LinksRepo $links, KeywordsRepo $keywords, EntitiesRepo $entities, SubtypesRepo $subtypes)
    {
        //
        $this->links = $links;
        $this->keywords = $keywords;
        $this->entities = $entities;
        $this->subtypes = $subtypes;
    }

    /**
     * Handle the command.
     *
     * @param  CreateLinkCommand  $command
     * @return void
     */
    public function handle(CreateLinkCommand $command)
    {
        $link_data = $this->links->getLinksData($command->url, ['keywords', 'entities']);

        // dd($link_data);

        $link_object = Links::make(
                $command->url,
                $link_data['content']['title'],
                $link_data['content']['content'],
                Auth::user()->id,
                sluggifyUrl($command->url),
                getDomainFromUrl($command->url),
                md5(sluggifyUrl($command->url))
            );

        $link = $this->links->save($link_object);

        $this->attachKeywords($link, $link_data['keywords']);
        
        $this->attachEntities($link, $link_data['entities']);

        Event::fire(new LinkWasCreated($link));

        return $link;
    }

    public function attachKeywords($link, $keywords)
    {
        foreach ($keywords as $keyword) {

            $keyword_object = Keywords::make($keyword['text'], str_slug($keyword['text']));

            $new_keyword = $this->keywords->save($keyword_object);

            $link->keywords()->attach($new_keyword->id, ['relevance' => $keyword['relevance']]);

        }
    }

    public function attachEntities($link, $entities)
    {
        foreach ($entities as $entity) {



            if(isset($entity['disambiguated']))
            {

                if (isset($entity['disambiguated']['website']) && isset($entity['disambiguated']['geo']))
                {
                    $entity_object = Entities::make($entity['type'], $entity['text'], str_slug($entity['text']), $entity['disambiguated']['name'], $entity['disambiguated']['website'], $entity['disambiguated']['geo']);

                }elseif(isset($entity['disambiguated']['website']) && !isset($entity['disambiguated']['geo']))
                {
                    $entity_object = Entities::make($entity['type'], $entity['text'], str_slug($entity['text']), $entity['disambiguated']['name'], $entity['disambiguated']['website']);

                }elseif(!isset($entity['disambiguated']['website']) && isset($entity['disambiguated']['geo'])){

                    $entity_object = Entities::make($entity['type'], $entity['text'], str_slug($entity['text']), $entity['disambiguated']['name'], $entity['disambiguated']['geo']);
                }else {
                    $entity_object = Entities::make($entity['type'], $entity['text'], str_slug($entity['text']));
                }


            } else {
                $entity_object = Entities::make($entity['type'], $entity['text'], str_slug($entity['text']));
            }

            

            $new_entity = $this->entities->save($entity_object);

            $link->entities()->attach($new_entity->id, ['relevance' => $entity['relevance'], 'count' => $entity['count']]);

            if(isset($entity['disambiguated']['subType'])){
                $this->attachSubtypes($new_entity, $entity['disambiguated']['subType']);
            }

        }
    }

    public function attachSubtypes($entity, $subtypes)
    {
        foreach ($subtypes as $name) {
            $subtype_object = Subtypes::make($name);

            $new_subtype = $this->subtypes->save($subtype_object);

            $entity->subtypes()->attach($new_subtype->id);
        }
    }


}
