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

        $result = $readability->init();

        $out = '';

        // if ($result) {
            return ['title' => $readability->getTitle()->textContent, 'content' => $readability->getContent()->innerHTML];
            // $out .= "<h2>".$readability->getTitle()->textContent . "</h2>". "\n\n";
            // $content = $readability->getContent()->innerHTML;
            // $out .= $content;
        // } else {

        //     throw new ErrorException;
        //     // return ['title' => 'Count not fetch title', 'content' => 'Could not fetch content'];
        // }

    }

}