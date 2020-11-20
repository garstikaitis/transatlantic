<?php

namespace App\Http\Controllers;

use App\Models\LocaleOrganization;
use Exception;
use App\Models\Project;
use App\Models\Translation;
use App\Models\LocaleProject;

class ProjectController extends Controller
{
    public function getProjects() {

        try {

			$this->validateInput(request()->all(), [
				'organizationId' => 'required|integer|exists:organizations,id'
			]);

			$projects = Project::where('organizationId', request('organizationId'))
				->with('locales', 'translations')
				->get();

            return response()->json(['success' => true, 'data' => $projects]);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }

	}

    public function getProject($id) {

        try {

			$project = Project::where('id', $id)->firstOrFail()->load('locales');

            return response()->json(['success' => true, 'data' => $project]);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }

	}
	
    public function createProject() {

        try {

			$this->validateInput(request()->all(), [
				'name' => 'required|string|min:3',
				'organizationId' => 'required|integer|exists:organizations,id',
				'localeIds' => 'required|array',
				'mainLocaleId' => 'required|integer|exists:locales,id',
			]);

			$project = new Project();
			$project->name = request('name');
			$project->organizationId = request('organizationId');

			$project->save();

			LocaleProject::create([
				'localeId' => request('mainLocaleId'), 
				'projectId' => $project->id, 
				'isMainLocale' => true,
			]);	

			LocaleOrganization::create([
				'localeId' => request('mainLocaleId'),
				'organizationId' => request('organizationId')
			]);

			foreach(request('localeIds') as $localeId) {
				LocaleProject::create([
					'localeId' => $localeId, 
					'projectId' => $project->id, 
					'isMainLocale' => false,
				]);		
				LocaleOrganization::create([
					'localeId' => $localeId,
					'organizationId' => request('organizationId')
				]);		
			}



            return response()->json(['success' => true, 'data' => $project->load('locales')], 201);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }

	}

	public function updateProject() {

		try {

			$this->validateInput(request()->all(), [
				'name' => 'required|string|min:3',
				'projectId' => 'required|integer|exists:projects,id',
			]);

			$project = Project::findOrFail(request('projectId'));

			$project->name = request('name');
			$project->save();

			return response()->json(['success' => true, 'data' => $project->load('locales')], 201);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }

	}
	
    public function deleteProject() {

        try {

			$this->validateInput(request()->all(), [
				'projectId' => 'required|integer|exists:projects,id'
			]);

			Project::where('id', request('projectId'))->delete();
			Translation::where('projectId', request('projectId'))->delete();
			LocaleProject::where('projectId', request('projectId'))->delete();

            return response()->json(['success' => true, 'data' => []]);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }

    }
}
