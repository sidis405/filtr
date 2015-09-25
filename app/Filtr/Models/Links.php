<?php

namespace Filtr\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\SearchIndex\Searchable;
use Laracasts\Presenter\PresentableTrait;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class Links extends Model implements Searchable, HasMedia
{

    use PresentableTrait, HasMediaTrait;

    protected $fillable = ['url', 'title', 'description', 'content', 'image', 'code', 'user_id', 'slug', 'domain', 'hash'];

    protected $presenter = 'Filtr\Presenters\LinksPresenter';

    /**
     * Returns an array with properties which must be indexed
     * @param string $title Title of the article
     * @param string $description Description of the article
     * @param string $content Content of the article
     * @param string $slug slug of the article
     * @param Filtr\Models\Entities $entities entities of the article
     * @param FIltr\Models\Keywords $keywords keywords of the article
     * @param string $domain Domain of the article
     * @return array
     */
    public function getSearchableBody()
    {
        $searchableProperties = [
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'slug' => $this->slug,
            'entities' => $this->entities,
            'keyword' => $this->keywords,
            'domain' => $this->domain
        ];

        return $searchableProperties;

    }

    /**
     * Return the type of the searchable subject
     *
     * @return string
     */
    public function getSearchableType()
    {
        return 'links';
    }

    /**
     * Return the id of the searchable subject
     * @param string $id ID of the link model
     * @return string
     */
    public function getSearchableId()
    {
        return $this->id;
    }

    
    public function user()
    {
        return $this->belongsTo('Filtr\Models\User');
    }

    public function externals()
    {
        return $this->hasMany('Filtr\Models\ExternalLinks', 'link_id');
    }

    public function entities()
    {
        return $this->belongsToMany('Filtr\Models\Entities', 'entity_link', 'link_id', 'entity_id')->withPivot('count', 'relevance')->orderBy('pivot_relevance', 'DESC')->withTimestamps();
    }

    public function keywords()
    {
        return $this->belongsToMany('Filtr\Models\Keywords', 'keyword_link', 'link_id', 'keyword_id')->withPivot('relevance')->orderBy('pivot_relevance', 'DESC')->withTimestamps();
    }

    public static function make($url, $title, $description, $image, $code, $content, $user_id, $slug, $domain, $hash)
    {   
        $staff = new static(compact('url', 'title', 'description', 'image', 'code', 'content', 'user_id', 'slug', 'domain', 'hash'));

        return $staff;
    }

    public function relatedByKeywords()
    {
        return \DB::select( \DB::raw("SELECT 
                    links.id, 
                    links.title,
                    links.slug as link_slug,
                    links.domain,
                    keywords.text,
                    keywords.slug as keyword_slug,
                    count(DISTINCT r2.keyword_id) as relevance
                    FROM keywords, keyword_link r1
                    INNER JOIN keyword_link r2 ON (r1.keyword_id = r2.keyword_id AND r1.link_id <> r2.link_id) 
                    INNER JOIN links ON (r2.link_id = links.id) 
                    WHERE r1.link_id = '{$this->id}' and keywords.id = r1.keyword_id 
                    GROUP BY links.id, keywords.id
                    ORDER BY relevance DESC"));
    }

    public function relatedByEntities()
    {
        return \DB::select( \DB::raw("SELECT 
                    links.id, 
                    links.title,
                    links.slug as link_slug,
                    links.domain,
                    entities.text,
                    entities.slug as entity_slug,
                    count(DISTINCT r2.entity_id) as relevance
                    FROM entities, entity_link r1
                    INNER JOIN entity_link r2 ON (r1.entity_id = r2.entity_id 
                                                       AND r1.link_id <> r2.link_id) 
                    INNER JOIN links ON (r2.link_id = links.id) 
                    WHERE r1.link_id = '{$this->id}' and entities.id = r1.entity_id 
                    GROUP BY links.id, entities.id
                    ORDER BY relevance DESC"));
    }
}
