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
            'locales' => 'required|array',
            'organizationId' => 'required|integer|exists:organizations,id',
        ]);

            $this->checkIfLocalesAreValid();

            $this->filterResultsByOrganizationId();

			$this->filterResultsByLocale();
			
			$this->setResults();

			return response()->json(['success' => true, 'data' => $this->results], 200);
			
        } catch (Exception $e) {

			return response()->json(['success' => false, 'message' => 'Error fetching results'], 500);
			
        }
    }

	private function checkIfLocalesAreValid() {

		foreach($this->request['locales'] as $locale)
		if(!$this->helpers->localeIsValid($locale)) abort(500, 'Locale is not valid');

	}

	private function filterResultsByOrganizationId() {
		
		$this->query = Translation::with('locale')->where('organizationId', $this->request['organizationId']);

	}

	private function filterResultsByLocale() {

		$this->query->whereHas('locale', function($localeQuery) {
			return $localeQuery->whereIn('iso', $this->request['locales']);
		});

	}

	private function setResults() {

		$this->results = $this->query->get();

	}
}
