<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Project;
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
	
    public function createProject() {

        try {

			$this->validateInput(request()->all(), [
				'name' => 'required|string|min:3',
				'organizationId' => 'required|integer|exists:organizations,id',
				'localeIds' => 'required|array'
			]);

			$project = new Project();
			$project->name = request('name');
			$project->organizationId = request('organizationId');

			$project->save();

			foreach(request('localeIds') as $localeId) {
				LocaleProject::create(['localeId' => $localeId, 'projectId' => $project->id]);
			}

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

            return response()->json(['success' => true, 'data' => []]);

        } catch(Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);

        }

    }
}
