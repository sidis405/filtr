<?php

namespace Filtr\Repositories;

use Filtr\Models\Links;
use Filtr\Repositories\ReadabilityRepo;
use Filtr\Repositories\SemanticRepo;

/**
* Links Repo
*/
class LinksRepo
{
    public $readability;
    public $semantics;
    
    public function __construct(ReadabilityRepo $readability, SemanticRepo $semantics)
    {
        $this->readability = $readability;
        $this->semantics = $semantics;
    }

    public function save(Links $link)
    {
        $link->save();

        return $link;
    }

    public function getAll()
    {
        return Links::with('keywords', 'entities.subtypes')->whereStatus(1)->get();
    }

    public function parseContent()
    {
        // $link = Links::with('keywords', 'entities')->find(22);
        // // dd($link->toArray());
        //     $link_text = $this->getContentLinks($link);
        //     // dd($link_text[1]);
        //     $this->parseKeywordsEntitiesOnce($link, $link_text[1]);

        $links = $this->getAll();

        foreach ($links as $link) {
            $this->parseItemsOnlyOnce($link, 'entities');
            $this->parseItemsOnlyOnce($link, 'keywords');
        }
    }

    public function parseItemsOnlyOnce($link, $type)
    {
        $content = $link->content;
        $obj = $link->$type;


        $link_text = $this->getContentLinks($link);



        foreach ($obj as $item) {

            if( ! isContainedInElementsOfArray($item->text, $link_text[1]) && $item->pivot->relevance > 0.5){
                $content = str_replace_first($item->text, '<a href="/' .$type. '/' . $item->slug . ' "class="' . $type . '">' .$item->text.'</a>', $content);
            }
        }

        $link->update(['content' => $content]);
    }

    public function getContentLinks($link)
    {
        $links = [];
        $link_text = [];

        $html = new \DOMDocument();

        $content = $link->content;
        libxml_use_internal_errors(true);
        $html->loadHTML($content);
        libxml_use_internal_errors(false);

        $anchors = $html->getElementsByTagName('a');
            foreach ($anchors as $anchor) {
                   $url = $anchor->getAttribute('href');
                   $text = $anchor->nodeValue;

                   $links[] = $url;
                   $link_text[] = $text;
            }

        return [$links, $link_text];
        
    }

    public function getLatest($howMany = 15)
    {
        return Links::with('keywords', 'entities.subtypes')->whereStatus(1)->latest()->simplePaginate($howMany);
    }

    public function getBySlug($slug)
    {
        return Links::with('keywords', 'entities.subtypes', 'entities.links', 'media', 'user')->whereSlug($slug)->first();
    }


    public function getReadability($url)
    {
        $content = $this->readability->filter($url);
        return $content;
    }

    public function getSemantics($content, $entities = [])
    {
        $meta = $this->semantics->extractSemanticData($content, $entities);
        return $meta;
    }
}
