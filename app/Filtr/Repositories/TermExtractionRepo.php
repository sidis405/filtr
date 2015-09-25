<?php

namespace Filtr\Repositories;

use Filtr\Utils\TermExtractor\TermExtractor;

/**
* Ter Extracting Repo
*/
class TermExtractionRepo
{
    public function extract($text)
    {

        $text = str_replace('&#13;', '', strip_tags($text));

        $out = [];
        $extractor = new TermExtractor();
        $terms = $extractor->extract($text);

        foreach ($terms as $term_info) {
            // index 0: term
            // index 1: number of occurrences in text
            // index 2: word count
            list($term, $occurrence, $word_count) = $term_info;

            if ($occurrence > 2 && strlen($term) > 2)
            {
                $out[] = [
                    'term' => $term,
                    'occurrence' => $occurrence,
                    'word_count' => $word_count,
                    'length'    => strlen($term)
                    ];
            }

            
        }


        $final = [];
        foreach ($out as $key => $row)
        {
            $final[$key] = $row['occurrence'];
        }
        array_multisort($final, SORT_DESC, $out);

        return $out;
    }

}
