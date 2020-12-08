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
	
	const TRANSLATIONS_PER_PAGE = 50;

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

			$this->paginateResults();

			$this->setResults();

			$this->setPaginationObject();

			$this->sortResults();
			
			$this->groupResults();

			return response()->json(['success' => true, 'data' => ['results' => $this->results, 'pagination' => $this->pagination]], 200);
			
        } catch (Exception $e) {

			return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
			
        }
    }

	private function filterResultsByProjectId() {
		
		$this->resultsQuery = Translation::with('locale')->where('projectId', $this->request['projectId']);

	}

	private function paginateResults() {

		$this->query = $this->resultsQuery->skip($this->request['page'] - 1 * self::TRANSLATIONS_PER_PAGE)->take(self::TRANSLATIONS_PER_PAGE);

	}

	private function setPaginationObject() {

		$count = $this->resultsQuery->count();

		$this->pagination = [
			'currentPage' => $this->request['page'],
			'totalResults' => $count,
			'totalPages' => intval(ceil($count / self::TRANSLATIONS_PER_PAGE)),
		];

	}

	private function setResults() {

		$this->results = $this->query->get();

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

		$this->results = $this->results->groupBy('transKey');

	}

}
