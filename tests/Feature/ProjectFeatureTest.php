<?php

namespace Tests\Feature;

use App\Models\LocaleProject;
use Tests\TestCase;
use App\Models\Project;
use App\Models\Translation;

class ProjectFeatureTest extends TestCase
{

	public function setUp(): void {

		parent::setUp();

		$this->withoutMiddleware();

	}

    public function test_user_can_get_projects()
    {

		$project = Project::factory()->create(['organizationId' => $this->organization->id]);

		LocaleProject::factory()->create(['localeId' => $this->locale->id, 'projectId' => $project->id]);

        $response = $this->callApiAsAuthUser('POST', '/api/projects', ['organizationId' => $this->organization->id]);

		$response->assertStatus(200);
		
		$json = $response->decodeResponseJson();

		$this->assertTrue($json['success']);

	}

    public function test_user_can_not_get_projects_with_wrong_input()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/projects', ['organizationId' => '123123123212321']);

		$response->assertStatus(500);
		
	}

    public function test_user_can_create_project()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/projects/create', [
			'name' => '123',
			'organizationId' => $this->organization->id,
			'mainLocaleId' => $this->locale->id,
			'localeIds' => [$this->locale->id]
		]);

		$response->assertStatus(201);

		$json = $response->decodeResponseJson();

		$this->assertTrue($json['success']);
		

		$this->assertDatabaseHas('projects', ['name' => '123']);
		$this->assertDatabaseHas('locale_project', ['localeId' => $this->locale->id, 'projectId' => $json['data']['id']]);

	}

    public function test_user_can_not_create_project_with_wrong_input()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/projects/create', [
			'name' => '123',
		]);

		$response->assertStatus(500);

		$json = $response->decodeResponseJson();

		$this->assertFalse($json['success']);

		$this->assertDatabaseMissing('projects', ['name' => '123']);
		$this->assertDatabaseMissing('locale_project', ['localeId' => $this->locale->id]);
		
	}

	public function test_user_can_update_project() {
	
		$response = $this->callApiAsAuthUser('POST', '/api/projects/update', [
			'name' => 'new name',
			'projectId' => $this->project->id,
		]);

		$response->assertStatus(201);

		$json = $response->decodeResponseJson();

		$this->assertTrue($json['success']);

		$this->assertDatabaseHas('projects', ['name' => 'new name']);
		$this->assertDatabaseMissing('projects', ['name' => $this->project->name]);
		

	}

	public function test_user_can_not_update_project_with_wrong_input() {
	
		$response = $this->callApiAsAuthUser('POST', '/api/projects/update', [
			'name' => 'new name',
			'projectId' => 1231231232131,
		]);

		$response->assertStatus(500);

		$json = $response->decodeResponseJson();

		$this->assertFalse($json['success']);

		$this->assertDatabaseMissing('projects', ['name' => 'new name']);
		$this->assertDatabaseHas('projects', ['name' => $this->project->name]);
		

	}

    public function test_user_can_delete_project()
    {

		$project = Project::factory()->create();

        $response = $this->callApiAsAuthUser('POST', '/api/projects/delete', [
			'projectId' => $project->id,
		]);

		$response->assertStatus(200);

		$json = $response->decodeResponseJson();

		$this->assertTrue($json['success']);

		$this->assertSoftDeleted('projects', ['id' => $project->id]);
		
	}

    public function test_user_can_not_delete_project_with_wrong_input()
    {

		$project = Project::factory()->create();

        $response = $this->callApiAsAuthUser('POST', '/api/projects/delete', [
			'projectId' => 1231231232,
		]);

		$response->assertStatus(500);

		$json = $response->decodeResponseJson();

		$this->assertFalse($json['success']);

		$this->assertDatabaseHas('projects', ['id' => $project->id]);
		
	}
	
}
