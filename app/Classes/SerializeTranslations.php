<?php

namespace App\Classes;

use Illuminate\Database\Eloquent\Collection;

class SerializeTranslations
{

	private $translations;
	private $translationsResult;

	public function __construct(Collection $translations)
	{
		$this->translations = $translations;
	}

	public function handle() {

		$this->getJsonStructureFromTransKeys();

		$this->setTranslations();

		return $this->results;

	}

	public function getJsonStructureFromTransKeys() {
		$mappedTranslations = [];

		foreach($this->translations as $translation) {
			$mappedTranslations[$translation->locale->iso][$translation->transKey] = $translation->transValue;
		}

		$traversed = $this->expandKeys($mappedTranslations);

		$this->translationsResult = $traversed;

	}

	private function expandKeys($arr) {
		$result = [];
		foreach($arr as $key => $value) {
			if (is_array($value)) $value = $this->expandKeys($value);
			foreach(array_reverse(explode(".", $key)) as $key) $value = [$key => $value];
			$result = array_merge_recursive($result, $value);
		}
		return $result;
	}

	public function setTranslations() {

		$this->results = $this->translationsResult;

	}
}