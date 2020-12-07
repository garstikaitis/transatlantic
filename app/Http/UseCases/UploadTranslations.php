<?php

namespace App\Http\UseCases;

use stdClass;
use Exception;
use App\Models\Locale;
use App\Contracts\UseCase;
use App\Models\Translation;
use App\Traits\ValidationTrait;
use Illuminate\Support\Collection;

class UploadTranslations implements UseCase
{
    use ValidationTrait;

	private array $request;
	private object $fileContents;
	public $tempKey;
	private Collection $translations;
    
    public function __construct(array $request)
    {
		$this->request = $request;
		$this->translations = collect();
    }

    public function handle()
    {
        try {
            $this->validateInput($this->request, [
                'projectId' => 'required|integer|exists:projects,id',
                'organizationId' => 'required|integer|exists:organizations,id',
                'file' => 'required|file',
			]);
			 
			$this->setFile();

			$this->getFileContents();

			$this->saveTranslationsToDb();

			$this->setResults();

            return response()->json(['success' => true, 'data' => $this->translations], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
	}

	private function setResults() {

		$results = Translation::with('locale')->where('projectId', $this->request['projectId'])->get();

		$this->translations = $results = $results->groupBy('transKey');

	}

	private function setFile() {

		$this->file = $this->request['file'];

	}

	private function saveTranslationsToDb() {

		foreach($this->fileContents as $locale => $fileContent) {

			$this->tempKey = '';

			foreach($fileContent as $key => $content) {


				
				$this->tempKey = $key;

				$trans = new stdClass();


				if(is_object($content)) {
					$trans = $this->recursivelyBuildTranslationObject($content, $locale, $this->tempKey);
				} else {
					$trans->transKey = $key;
					$trans->transValue = $content;
					$this->saveTranslation($locale, $trans);
				}

				
			}
		}

	}

	private function saveTranslation(string $locale, object $trans) {
		$localeModel = Locale::where('iso', $locale)->firstOrFail();

		$translation = new Translation();
		$translation->transKey = $trans->transKey;
		$translation->transValue = $trans->transValue;
		$translation->localeId = $localeModel->id;
		$translation->userId = auth()->id();
		$translation->projectId = $this->request['projectId'];
		$translation->organizationId = $this->request['organizationId'];

		$translation->save();

	}

	private function recursivelyBuildTranslationObject($transValue, $locale, $parentKey) {
		$returnValue = new stdClass();
		foreach($transValue as $key => $value) {
			$this->tempKey = $this->tempKey . '.' . $key;
			if(!is_string($value)) {
				$returnValue = $this->recursivelyBuildTranslationObject($value, $locale, $parentKey);
			} else {
				$key = $this->tempKey;
				$returnValue->transKey = $key;
				$returnValue->transValue = $value;
				$this->saveTranslation($locale, $returnValue);
				$this->tempKey = $parentKey;
			}
		}
		return $returnValue;
	}

	private function getFileContents() {

		$json = file_get_contents($this->file);
		$this->fileContents = json_decode($json);

	}
	
}
