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
				'o' => 'required|integer|exists:organizations,id', // organizationId
				'p' => 'required|integer|exists:projects,id', // projectId
				't' => 'required|string', // token
			]);

			$this->validateToken();

			$translations = Translation::with('locale')->where('projectId', request('p'))->get();

			$translations = (new SerializeTranslations($translations))->handle();

            return response()->json($translations);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }


    }
}
