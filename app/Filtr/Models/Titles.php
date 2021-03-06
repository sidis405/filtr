<?php

namespace Filtr\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\SearchIndex\Searchable;

/**
* @property string $title Title
* @property string $slug Slug
* @property string $domain Domain
* @property string $id Id of the titles models
 */

class Titles extends Model implements Searchable
{

    protected $table = 'links';

    /**
     * Returns an array with properties which must be indexed
     * @return array
     */
    public function getSearchableBody()
    {
        $searchableProperties = [
            'title' => $this->title,
            'slug' => $this->slug,
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
        return 'titles';
    }

    /**
     * Return the id of the searchable subject
     * @return string
     */
    public function getSearchableId()
    {
        return $this->id;
    }
}
