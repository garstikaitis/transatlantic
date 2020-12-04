<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Translation;
use App\Models\LocaleOrganization;
use App\Http\UseCases\GetTranslations;
use App\Http\UseCases\UploadTranslations;

class TranslationController extends Controller
{

    public function getTranslations() {

        try {

            return (new GetTranslations(request()->all()))->handle();

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => 'Error getting translations'], 500);

        }

    }

    public function uploadTranslations() {

        try {

            return (new UploadTranslations(request()->all()))->handle();

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

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

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

    }

    public function updateTranslation() {

        try {
            $this->validateInput(
                request()->all(), [
                    'translationId' => 'integer|required',
                    'transKey' => 'string|required',
                    'transValue' => 'string|required',
                    'localeId' => 'integer|required|exists:locales,id',
                    'organizationId' => 'integer|required|exists:organizations,id',
                    'userId' => 'required|exists:users,id',
                    'projectId' => 'required|integer|exists:projects,id'
                ]
            );

            if(!$this->checkIfLocaleIsEnabledForOrganization(request('localeId'), request('organizationId'))) {

                abort(500, 'Locale is disabled for organization');
                
            }

            $translation = Translation::where('id', request('translationId'))->firstOrFail();
            $translation->transKey = request('transKey');
            $translation->transValue = request('transValue');
            $translation->localeId = request('localeId');
            $translation->organizationId = request('organizationId');
            $translation->userId = request('userId');
            $translation->projectId = request('projectId');

            $translation->update();

            return response()->json(['success' => true, 'data' => $translation], 200);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

    }

    public function deleteTranslation() {

        try {
            
            $this->validateInput(
                request()->all(), [
                    'projectId' => 'integer|required|exists:projects,id',
                    'translationKeys' => 'required|array',
                ]
            );

            foreach(request('translationKeys') as $translationKey) {

                Translation::where('transKey', $translationKey)->delete();

            }

            return response()->json(['success' => true, 'data' => null], 200);


        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

    }

    private function checkIfLocaleIsEnabledForOrganization(int $localeId, int $organizationId) {

        $mapCount = LocaleOrganization::where('organizationId', $organizationId)->where('localeId', $localeId)->count();

        return $mapCount > 0;

    }
}
