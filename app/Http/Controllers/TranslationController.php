<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Translation;
use App\Traits\ValidationTrait;
use App\Models\LocaleOrganization;
use App\Http\UseCases\GetTranslations;

class TranslationController extends Controller
{

    public function getTranslations() {

        try {

            return (new GetTranslations(request()->all()))->handle();

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => 'Error getting translations'], 500);

        }

    }

    public function createTranslation() {

        try {
            
            $this->validateInput(
                request()->all(), [
                    'transKey' => 'string|required',
                    'transValue' => 'string|required',
                    'localeId' => 'integer|required|exists:locales,id',
                    'organizationId' => 'integer|required|exists:organizations,id',
                    'userId' => 'required|exists:users,id'
                ]
            );

            if(!$this->checkIfLocaleIsEnabledForOrganization(request('localeId'), request('organizationId'))) {

                abort(500, 'Locale is disabled for organization');
                
            }

            $translation = Translation::create(request()->all());

            return response()->json(['success' => true, 'data' => $translation], 201);

        } catch (Exception $e) {

            // dd($e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

    }

    private function checkIfLocaleIsEnabledForOrganization(int $localeId, int $organizationId) {

        $mapCount = LocaleOrganization::where('organizationId', $organizationId)->where('localeId', $localeId)->count();

        return $mapCount > 0;

    }
}
