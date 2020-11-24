<?php

namespace App\Http\UseCases;

use Exception;
use App\Contracts\UseCase;
use App\Models\Translation;
use App\Traits\ValidationTrait;

class GetTranslations implements UseCase {

	use ValidationTrait;

	private array $request;
	private $query;
	
	public function __construct(array $request)
	{

		$this->request = $request;

	}

	public function handle() {
        try {
            $this->validateInput($this->request, [
				'projectId' => 'required|integer|exists:projects,id',
				'searchValue' => 'nullable|string',
     		]);


			$this->filterResultsByProjectId();

			$this->setResults();
			
			$this->sortResults();

			$this->groupAndSetResults();

			return response()->json(['success' => true, 'data' => $this->results], 200);
			
        } catch (Exception $e) {

			return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
			
        }
    }

	private function filterResultsByProjectId() {
		
		$this->query = Translation::with('locale')->where('projectId', $this->request['projectId']);

	}

	private function setResults() {

		$this->results = $this->query->get();

	}

	private function sortResults() {

		if(in_array('searchValue', $this->request)) {
			$this->results = $this->results->map(function($translation, $index) {
				$searchValue = str_replace(' ', '', strtolower($this->request['searchValue']));
				$transValue = str_replace(' ', '', strtolower($translation->transValue));
				$count = $this->countSubstrings($searchValue, $transValue);
				$translation->sortingRank = $count;
				return $translation;
			});
			$this->results->sortBy('sortingRank');
		}
	}

	private function countSubstrings($searchValue, $transValue) {

		$searchValueArray = str_split($searchValue);
		$transValueArray = str_split($transValue);
		$count = 0;
		foreach($searchValueArray as $index => $letter) {
			if(!array_key_exists($index, $transValueArray)) break;
			if($letter === $transValueArray[$index]) {
				$count++;
			}
		}
		return $count;

	}

	public function groupAndSetResults() {

		$this->results = $this->results->groupBy('transKey');

	}

}
