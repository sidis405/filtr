<?php

namespace Filtr\Utils\TermExtractor\Filters;

use Filtr\Utils\TermExtractor\Filters\FilterInterface;

class DefaultFilter implements FilterInterface {

	private $singleStrengthMinOccur;
	private $noLimitStrength;

	public function __construct($singleStrengthMinOccur = 3, $noLimitStrength = 2) {
		$this->singleStrengthMinOccur = $singleStrengthMinOccur;
		$this->noLimitStrength = $noLimitStrength;
	}

	public function accept($word, $occur, $strength, $allTerms) {
		return (($strength == 1 && $occur >= $this->singleStrengthMinOccur) ||
						($strength >= $this->noLimitStrength));
	}
}
