<?php

namespace Filtr\Models;

use Spatie\SearchIndex\Searchable;

use Illuminate\Database\Eloquent\Model;

class Keywords extends Model implements Searchable
{
    protected $fillable = ['text', 'slug'];

    /**
     * Returns an array with properties which must be indexed
     * @property string $text Text of the keyword
     * @return array
     */
    public function getSearchableBody()
    {
        $searchableProperties = [
            'text' => $this->text
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
        return 'keywords';
    }

    /**
     * Return the id of the searchable subject
     * @property string $this->id ID of the keyword
     * @return string
     */
    public function getSearchableId()
    {
        return $this->id;
    }

    public function entities()
    {
        return $this->belongsToMany('Filtr\Models\Entities', 'entity_keyword', 'keyword_id', 'entity_id')->withTimestamps();
    }

    public function links()
    {
        return $this->belongsToMany('Filtr\Models\Links', 'keyword_link', 'keyword_id', 'link_id')->withTimestamps();
    }

    public static function make($text, $slug)
    {   
        $keyword = new static(compact('text', 'slug'));

        return $keyword;
    }
}
