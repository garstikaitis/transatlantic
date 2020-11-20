<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Locale;
use App\Models\Translation;
use Illuminate\Http\Request;
use App\Classes\SerializeTranslations;
use App\Http\UseCases\GetTranslations;
use App\Models\LocaleProject;

class ClientController extends Controller
{
    public function getOrganizationProjectTranslations() {

        try {

			$this->validateInput(request()->all(), [
				'organizationId' => 'required|integer|exists:organizations,id',
				'projectId' => 'required|integer|exists:projects,id',
				'token' => 'required|string',
			]);

			$this->validateToken();

			$translations = Translation::with('locale')->where('projectId', request('projectId'))->get();

			$translations = (new SerializeTranslations($translations))->handle();

            return response()->json(['success' => true, 'data' => $translations]);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }


    }
}
