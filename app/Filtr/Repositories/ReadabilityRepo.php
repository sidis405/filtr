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
        if( ! $html = file_get_contents($url) ) {

            abort(405);
            // throw new ErrorException('Could not fetch content');

        }else{

            $readability = new Readability($html, $url);

            $readability->debug = $debug;
            $readability->convertLinksToFootnotes = $footnotes;

            if ($readability->init()) {
                return ['title' => $readability->getTitle()->textContent, 'content' => $readability->getContent()->innerHTML];
            } else {
                abort(406);
                // throw new ErrorException('Could not fetch content');
            }

        }

    }

}
