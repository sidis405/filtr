<?php

namespace App\Jobs\Links;

use App\Events\Links\LinkWasCreated;
use App\Events\Links\LinkWasProcessed;
use App\Jobs\Job;
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
use Filtr\Repositories\ReadabilityRepo;
use Filtr\Repositories\SemanticRepo;
use Filtr\Repositories\SubtypesRepo;
use Filtr\Utils\Alchemy\AlchemyAPI;
use Filtr\Utils\Media\Media;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use SearchIndex;

class PostProcessLink extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $event;
    protected $link;
    protected $readability;
    protected $embeds;
    protected $entities;
    protected $keywords;
    protected $links;
    protected $subtypes;
    protected $media;
    protected $external_links;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(LinkWasCreated $event)
    {
        $this->event                = json_decode(json_encode($event));
        $this->link                 = Links::find($this->event->link->id);
        $this->readability          = $this->event->readability;
        $this->embeds               = new EmbedsRepo;
        $this->entities             = new EntitiesRepo;
        $this->keywords             = new KeywordsRepo;
        $this->links                = new LinksRepo(new ReadabilityRepo, new SemanticRepo(new AlchemyAPI));
        $this->subtypes             = new SubtypesRepo;
        $this->media                = new Media;
        $this->external_links       = new ExternalLinksRepo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // \Log::info($this->event->link->slug);

        //GET REMOTE DATA
        // logger('getting remote emantic and embed data');
        $semantic_data = $this->links->getSemantics($this->readability->content, ['keywords', 'entities']);
        $embed_data = $this->embeds->fetch($this->event->link->url);

        

        // PROCESS Images and parse image link
        // logger('attaching images and parsing image links');
        $this->attachImages($this->link, $embed_data);
        
        $parsed_for_images_content = $this->parseImageLinks($this->link);



        //UPDATE LINK WITH EMBED_DATA AND NEW IMAGE LINKS
        // logger('persisiting the model with the newl collected data');
        $this->link->update([
            'description' => $embed_data['description'],
            'image' => strtok($embed_data['image'], '?'),
            'code' => $embed_data['code'],
            'content' => $parsed_for_images_content
            ]);
        

        //ATTACH RELATED DATA
        // logger('attaching keywords');
        $this->attachKeywords($this->link, $semantic_data['keywords']);
        
        // logger('attaching entities');
        $this->attachEntities($this->link, $semantic_data['entities']);
        
        // logger('attaching external links');
        $this->attachExternalLinks($this->link);


        // logger('ES INdexing');
        SearchIndex::upsertToIndex($this->link);

        Event::fire(new LinkWasProcessed($this->link));
    }



    public function attachExternalLinks($link)
    {
        $html = new \DOMDocument();

        $content = $link->content;
        libxml_use_internal_errors(true);
        $html->loadHTML($content);
        libxml_use_internal_errors(false);

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
