<?php

namespace Filtr\Utils\TermExtractor;

class Tagger {

	// Modified by jpt - Turian: https://github.com/turian/topia.termextract
	// regex [^\W\d_] = [a-zA-Z] with Unicode alphabetic character.
	// See: http://stackoverflow.com/questions/2039140/python-re-how-do-i-match-an-alpha-character/2039476#2039476
	const TERM_SPEC = '!([\W\d_]*)(([^\W\d_]?[-\.]?)*[^\W\d_])([\W\d_]*[^\W\d_]*)!u';

	private $dict;
	private $language;

	public function __construct($language = 'english') {
		$this->dict = array();
		$this->language = $language;
	}

	public function initialize($use_apc = false) {
		$use_apc = ($use_apc && function_exists('apc_fetch'));
		//echo "Using APC: $use_apc.\n";
		if ($use_apc) {
			$this->dict = apc_fetch('ff_te_dict_'.$this->language);
			if ($this->dict !== false) {
				//echo "Loaded from APC!\n";
				return;
			}
		}
		$fh = fopen(dirname(__FILE__).'/data/'.$this->language.'.txt', 'r');	
		while($line = fgets($fh)) {
			$tags = array_slice(explode(' ', rtrim($line)), 0, 2);
			$this->dict[$tags[0]] = $tags[1];
		}
		fclose($fh);
		if ($use_apc) {
			apc_store('ff_te_dict_'.$this->language, $this->dict);
			//echo "Stored in APC!\n";
		}
	}

	private function correctDefaultNounTag(&$tagged_term) {
		// Determine whether a default noun is plural or singular.
		list($term, $tag, $norm) = $tagged_term;
		if ($tag == 'NND') {
			if (substr($term, -1) == 's') {
				$tagged_term[1] = 'NNS';
				$tagged_term[2] = substr($term, 0, -1);
			} else {
				$tagged_term[1] = 'NN';
			}
		}
	}

	private function verifyProperNounAtSentenceStart($idx, &$tagged_term, &$tagged_terms) {
		// Verify that noun at sentence start is truly proper.
		list($term, $tag, $norm) = $tagged_term;
		if (($tag == 'NNP' || $tag == 'NNPS') && ($idx == 0 || $tagged_terms[$idx-1][1] == '.')) {
			$lower_term = strtolower($term);
			if (isset($this->dict[$lower_term])) {
				$lower_tag = $this->dict[$lower_term];
				if ($lower_tag == 'NN' || $lower_tag == 'NNS') {
					$tagged_term[0] = $tagged_term[2] = $lower_term;
					$tagged_term[1] = $lower_tag;
				}
			}
		}
	}

	private function determineVerbAfterModal($idx, &$tagged_term, &$tagged_terms) {
		// Determine the verb after a modal verb to avoid accidental noun detection.
		list($term, $tag, $norm) = $tagged_term;
		if ($tag != 'MD') return;
		$len_terms = count($tagged_terms);
		$idx += 1;
		while ($idx < $len_terms) {
			if ($tagged_terms[$idx][1] == 'RB') {
				$idx += 1;
				continue;
			}
			if ($tagged_terms[$idx][1] == 'NN') {
				$tagged_terms[$idx][1] = 'VB';
			}
			break;
		}
	}

	private function normalizePluralForms($idx, &$tagged_term, &$tagged_terms) {
		list($term, $tag, $norm) = $tagged_term;
		if (($tag == 'NNS' || $tag == 'NNPS') && ($term == $norm)) {
			// Plural form ends in "s"
			$singular = substr($term, 0, -1);
			if ((substr($term, -1) == 's') && isset($this->dict[$singular])) {
				$tagged_term[2] = $singular;
				return;
			}
			// Plural form ends in "es"
			$singular = substr($term, 0, -2);
			if ((substr($term, -2) == 'es') && isset($this->dict[$singular])) {
				$tagged_term[2] = $singular;
				return;
			}
			// Plural form ends in "ies" (from "y")
			$singular = substr($term, 0, -3).'y';
			if ((substr($term, -3) == 'ies') && isset($this->dict[$singular])) {
				$tagged_term[2] = $singular;
				return;
			}
		}
	}

	public function tokenize($text) {
		$terms = array();
		$parts = preg_split('!\s!', $text);
		foreach ($parts as $term) {
			// If the term is empty, skip it, since we probably just have
			// multiple whitespace cahracters.
			if ($term == '') continue;
			// Now, a word can be preceded or succeeded by symbols, so let's
			// split those out
			if (!preg_match(self::TERM_SPEC, $term, $match)) {
				$terms[] = $term;
				continue;
			} else {
				array_shift($match);
				foreach ($match as $subTerm) {
					if ($subTerm != '') $terms[] = $subTerm;
				}
			}
		}
		return $terms;
	}

	// $terms should be an array produced by the tokenize() method
	// if a string is provided, we'll pass it to tokenize() first
	public function tag($terms) {
		if (is_string($terms)) {
			// treat $terms as input string
			$terms = $this->tokenize($terms);
		}
		$tagged_terms = array();
		// Phase 1: Assign the tag from the lexicon. If the term is not found,
		// it is assumed to be a default noun (NND).
		foreach ($terms as $term) {
			$tagged_terms[] = array(
				$term,
				isset($this->dict[$term]) ? $this->dict[$term] : 'NND',
				$term
			);
		}
		// Phase 2: Run through some rules to improve the term tagging and
		// normalized form.
		foreach ($tagged_terms as $idx => &$tagged_term) {
			// rules
			$this->correctDefaultNounTag($tagged_term);
			$this->verifyProperNounAtSentenceStart($idx, $tagged_term, $tagged_terms);
			$this->determineVerbAfterModal($idx, $tagged_term, $tagged_terms);
			$this->normalizePluralForms($idx, $tagged_term, $tagged_terms);
		}
		return $tagged_terms;
	}
}
