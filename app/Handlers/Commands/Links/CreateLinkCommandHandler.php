<?php

namespace App\Handlers\Commands\Links;

use App\Commands\Links\CreateLinkCommand;
use App\Events\Links\LinkWasCreated;
use Event;
use Filtr\Models\Entities;
use Filtr\Models\ExternalLinks;
use Filtr\Models\Keywords;
use Filtr\Models\Links;
use Filtr\Models\Subtypes;
use Filtr\Repositories\EmbedsRepo;
use Filtr\Repositories\EntitiesRepo;
use Filtr\Repositories\ExternalLinksRepo;
use Filtr\Repositories\KeywordsRepo;
use Filtr\Repositories\LinksRepo;
use Filtr\Repositories\SubtypesRepo;
use Filtr\Utils\Media\Media;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use SearchIndex;


class CreateLinkCommandHandler
{
    public $links;
    public $keywords;
    public $entities;
    public $subtypes;
    public $embeds;
    private $media;
    private $external_links;

    /**
     * Create the command handler.
     *
     * @return void
     */
    public function __construct(LinksRepo $links, KeywordsRepo $keywords, EntitiesRepo $entities, SubtypesRepo $subtypes, EmbedsRepo $embeds, Media $media, ExternalLinksRepo $external_links)
    {
        //
        $this->links = $links;
        $this->keywords = $keywords;
        $this->entities = $entities;
        $this->subtypes = $subtypes;
        $this->embeds = $embeds;
        $this->media = $media;
        $this->external_links = $external_links;
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

        $embed_data = $this->embeds->fetch($command->url);

        // dd($link_data);

        $link_object = Links::make(
                $command->url,
                $link_data['content']['title'],
                $embed_data['description'],
                strtok($embed_data['image'], '?'),
                $embed_data['code'],
                $link_data['content']['content'],
                Auth::user()->id,
                sluggifyUrl($command->url),
                getDomainFromUrl($command->url),
                md5(sluggifyUrl($command->url))
            );

        $link = $this->links->save($link_object);

        $this->attachImages($link, $embed_data);
        
        $new_content = $this->parseImageLinks($link);

        $link->update(['content' => $new_content]);

        $this->attachKeywords($link, $link_data['keywords']);
        
        $this->attachEntities($link, $link_data['entities']);
        
        $this->attachExternalLinks($link);

        SearchIndex::upsertToIndex($link);

        Event::fire(new LinkWasCreated($link));

        return $link;
    }

    public function attachExternalLinks($link)
    {
        $html = new \DOMDocument();

        $content = $link->content;

        $html->loadHTML($content);

        $anchors = $html->getElementsByTagName('a');
            foreach ($anchors as $anchor) {
                   $url = $anchor->getAttribute('href');

                   $external_links_object = ExternalLinks::make($link->id, $url);

                    $new_external_link = $this->external_links->save($external_links_object);
            }
    }

    public function parseImageLinks($link)
    {
        $images = $link->getMedia();

        $content = $link->content;

        for($i = 0; $i < count($images); $i++)
        {
            $find = urldecode($images[$i]['custom_properties']['original_url']);
            $replace= $images[$i]->getUrl();

            $content = str_replace($find, $replace, $content);
        }

        return $content;
    }

    public function attachImages($link, $embed_data)
    {
        $image_src = $this->media->attach($link, strtok($embed_data['image'], '?'));

        $link->update(['image' => $image_src]);

        foreach(getUniquImageUrls($embed_data['images'], $embed_data['image']) as $image)
        {
           $this->media->attach($link, $image);
        }
    }

    public function attachKeywords($link, $keywords)
    {
        foreach ($keywords as $keyword) {

            $keyword_object = Keywords::make($keyword['text'], str_slug($keyword['text']));

            $new_keyword = $this->keywords->save($keyword_object);

            SearchIndex::upsertToIndex($new_keyword);

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
