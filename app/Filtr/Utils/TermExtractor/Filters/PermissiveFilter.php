<?php

namespace Filtr\Utils\TermExtractor\Filters;

use Filtr\Utils\TermExtractor\Filters\FilterInterface;

class PermissiveFilter implements FilterInterface {
    
	public function accept($word, $occur, $strength, $allTerms) {
		return true;
	}
}
