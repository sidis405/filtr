<?php

function sluggifyUrl($url)
{
    return str_slug(str_replace(['.', '/'] , '-', str_replace(['www', 'http://'], '', $url)));
}

function getDomainFromUrl($url)
{
     $parsed_url = parse_url($url);
     return str_replace('www.', '', $parsed_url['host']);
}

function mergeRelated($keywords, $entities)
{
    $out = [];

    foreach($entities as $ent)
    {
        $out[$ent->link_slug]['info'] = ['title' => $ent->title, 'domain' => $ent->domain];

        if ( ! isset ($out[$ent->link_slug]['matches'][$ent->entity_slug])){
            $out[$ent->link_slug]['matches'][$ent->entity_slug][] = ['text' => $ent->text, 'type' => 'entity'];
        }

        if( ! isset ($out[$ent->link_slug]['relevance'])) $out[$ent->link_slug]['relevance'] = 0;

        $out[$ent->link_slug]['relevance']++;
        
    }

    foreach($keywords as $key)
    {
        $out[$key->link_slug]['info'] = ['title' => $key->title,  'domain' => $key->domain];

        if ( ! isset ($out[$key->link_slug]['matches'][$key->keyword_slug])){
            $out[$key->link_slug]['matches'][$key->keyword_slug][] = ['text' => $key->text, 'type' => 'keyword'];
        }

        if( ! isset ($out[$key->link_slug]['relevance'])) $out[$key->link_slug]['relevance'] = 0;

        $out[$key->link_slug]['relevance']++;
    }

        $final = [];
        foreach ($out as $key => $row)
        {
            $final[$key] = $row['relevance'];
        }
        array_multisort($final, SORT_DESC, $out);

        return $out;
    

    return $out;
}