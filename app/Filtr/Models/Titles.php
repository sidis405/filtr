<?php

namespace Filtr\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\SearchIndex\Searchable;

class Titles extends Model implements Searchable
{

    protected $table = 'links';

    /**
     * Returns an array with properties which must be indexed
     * @property string $this->title Title
     * @property string $this->slug Slug
     * @property string $this->domain Domain
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
     * @property string $id Id of the titles models
     * @return string
     */
    public function getSearchableId()
    {
        return $id;
    }
}
