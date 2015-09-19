<?php

namespace Filtr\Repositories;

use Filtr\Utils\Readability\Readability;

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

        $result = $readability->init();

        $out = '';

        if ($result) {
            $out .= "<h2>".$readability->getTitle()->textContent . "</h2>". "\n\n";
            $content = $readability->getContent()->innerHTML;
            $out .= $content;
        } else {
            $out .= 'Looks like we couldn\'t find the content. :(';
        }
        return $out;
    }

}