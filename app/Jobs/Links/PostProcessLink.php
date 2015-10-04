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

        //GET REMOTE DATA
        $semantic_data = $this->links->getSemantics($this->readability->content, ['keywords', 'entities']);
        $embed_data = $this->embeds->fetch($this->event->link->url);

        

        // PROCESS Images and parse image link
        $this->attachImages($this->link, $embed_data);
        
        $parsed_for_images_content = $this->parseImageLinks($this->link);



        //UPDATE LINK WITH EMBED_DATA AND NEW IMAGE LINKS
        $this->link->update([
            'description' => $embed_data['description'],
            // 'image' => strtok($embed_data['image'], '?'),
            'code' => $embed_data['code'],
            'author_name' => $embed_data['author_name'],
            'content' => $parsed_for_images_content
            ]);
        

        //ATTACH RELATED DATA
        $this->attachKeywords($this->link, $semantic_data['keywords']);
        
        $this->attachEntities($this->link, $semantic_data['entities']);
        
        $this->attachExternalLinks($this->link);

        $this->link->update(['status' => 1]);

        \Cache::forget($this->link->slug);

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

                   $this->external_links->save($external_links_object);
            }
    }

    public function parseImageLinks($link)
    {
        $images = $link->getMedia();

        $content = $link->content;

        $total_images_count = count($images);

        for($i = 0; $i < $total_images_count; $i++)
        {
            $find = urldecode($images[$i]['custom_properties']['original_url']);
            $replace= $images[$i]->getUrl();

            $content = str_replace($find, $replace.'?', $content);
        }

        return $content;
    }

    public function attachImages($link, $embed_data)
    {
        $image_src = $this->media->attach($link, strtok($embed_data['image'], '?'));

        $link->update(['image' => $image_src]);

        foreach(getUniquImageUrls($embed_data['images'], $embed_data['image']) as $image)
        {
            if(strlen($image) > 5 ){
                $this->media->attach($link, $image);
            }
        }
    }

    /**
     * Persist keywords
     * @param  Links $link
     * @param  array $keywords
     * @property string $new_keyword->id Id of the keyword
     * @return void           
     */
    public function attachKeywords($link, $keywords)
    {
        foreach ($keywords as $keyword) {

            $keyword_object = Keywords::make($keyword['text'], str_slug($keyword['text']));

            $new_keyword = $this->keywords->save($keyword_object);

            SearchIndex::upsertToIndex($new_keyword);

            $link->keywords()->attach($new_keyword->id, ['relevance' => $keyword['relevance']]);

        }
    }

    /**
     * Persiste Entitties
     * @param  Links $link
     * @param  array $entities
     * @property string $new_entity->id Id of the new entity
     * @return void           
     */
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

    /**
     * Attach Subtypes
     * @param  Entities $entity   
     * @property string $id Subtype Id To Attach
     * @param  Subtypes $subtypes 
     * @return void
     */
    public function attachSubtypes($entity, $subtypes)
    {
        foreach ($subtypes as $name) {
            $subtype_object = Subtypes::make($name);

            $new_subtype = $this->subtypes->save($subtype_object);

            $entity->subtypes()->attach($new_subtype->id);
        }
    }
}
