<?php

namespace Filtr\Utils\TermExtractor\Filters;

interface FilterInterface {
    
    public function accept($word, $occur, $strength, $allTerms);

}