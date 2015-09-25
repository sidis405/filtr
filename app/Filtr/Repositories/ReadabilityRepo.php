<?php

namespace Filtr\Repositories;

use Filtr\Utils\Readability\Readability;
use ErrorException;
/**
* Filter Class
*/
class ReadabilityRepo
{
    
    function filter($url, $footnotes = false, $debug = false)
    {
        $html = file_get_contents($url);

        $readability = new Readability($html, $url);

        $readability->debug = $debug;
        $readability->convertLinksToFootnotes = $footnotes;

        if ($readability->init()) {
            return ['title' => $readability->getTitle()->textContent, 'content' => $readability->getContent()->innerHTML];
        } else {

            throw new ErrorException('Could not fetch content');
        }

    }

}
