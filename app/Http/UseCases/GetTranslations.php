<?php

namespace App\Http\UseCases;

use Exception;
use App\Contracts\UseCase;
use App\Models\Translation;
use App\Classes\LocaleHelpers;
use App\Traits\ValidationTrait;

class GetTranslations implements UseCase {

	use ValidationTrait;

	private array $request;
	private LocaleHelpers $helpers;
	private $query;
	
	public function __construct(array $request)
	{
		$this->request = $request;
		$this->helpers = new LocaleHelpers();

	}

	public function handle() {
        try {
            $this->validateInput($this->request, [
            	'projectId' => 'required|integer|exists:projects,id',
     		]);


            $this->filterResultsByProjectId();

			
			$this->setResults();

			return response()->json(['success' => true, 'data' => $this->results], 200);
			
        } catch (Exception $e) {

			return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
			
        }
    }

	private function filterResultsByProjectId() {
		
		$this->query = Translation::with('locale')->where('projectId', $this->request['projectId']);

	}

	private function setResults() {

		$results = $this->query->get();

		$results = $results->groupBy('transKey');

		$this->results = $results;

	}
}
