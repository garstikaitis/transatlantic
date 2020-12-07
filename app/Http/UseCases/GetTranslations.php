<?php

namespace App\Http\UseCases;

use Exception;
use App\Contracts\UseCase;
use App\Models\Translation;
use App\Traits\ValidationTrait;
use Illuminate\Pagination\Paginator;

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
				'page' => 'required|integer'
     		]);

			$this->filterResultsByProjectId();

			$this->setResults();

			$this->paginateResults();
			
			$this->sortResults();
			
			$this->groupResults();

			return response()->json(['success' => true, 'data' => $this->results], 200);
			
        } catch (Exception $e) {

			return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
			
        }
    }

	private function filterResultsByProjectId() {
		
		$this->query = Translation::with('locale')->where('projectId', $this->request['projectId']);

	}

	private function paginateResults() {

		$paginator = new Paginator($this->results, 50, $this->request['page']);

		$this->results = $paginator;

	}

	private function setResults() {

		$this->results = $this->query->paginate();

	}

	private function sortResults() {

		if(isset($this->request['searchValue'])) {
			$results = $this->results['data']->map(function($translation, $index) {
				$searchValue = str_replace(' ', '', strtolower($this->request['searchValue']));
				$transValue = str_replace(' ', '', strtolower($translation->transValue));
				$count = $this->countSubstrings($searchValue, $transValue);
				$translation->sortingRank = $count;
				return $translation;
			});
			$this->results['data'] = $results->sortByDesc('sortingRank')->values();
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

	public function groupResults() {

		$results = $this->results['data'];

		$grouped = collect($results)->groupBy('transKey');

		$this->results['data'] = $grouped;

	}

	// public function paginateResults() {

	// 	lad($this->results);

	// }

}
