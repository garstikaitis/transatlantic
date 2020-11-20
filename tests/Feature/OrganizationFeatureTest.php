<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Locale;
use App\Models\Project;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\LocaleOrganization;

class OrganizationFeatureTest extends TestCase
{

	public function setUp(): void {

		parent::setUp();

		$this->withoutMiddleware();

	}

    public function test_user_can_get_all_locales()
    {

		Organization::factory()->create();

        $response = $this->callApiAsAuthUser('GET', '/api/organizations');

		$response->assertStatus(200);

		$response->assertJsonCount(5, 'data');
		
	}

    public function test_user_can_create_organization()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/organizations', ['name' => 'Test', 'subdomain' => 'test']);

		$response->assertStatus(201);

		$this->assertTrue($response['success']);

		$this->assertDatabaseHas('organizations', ['name' => 'Test']);
		
	}

	public function test_user_can_not_create_organization_with_wrong_input() {

		$response = $this->callApiAsAuthUser('POST', '/api/organizations', ['999' => 'Test', '123' => 'test']);

		$response->assertStatus(500);

		$this->assertFalse($response['success']);

		$this->assertDatabaseMissing('organizations', ['name' => 'Test']);

	}

	public function test_user_can_not_be_associated_with_organization_with_wrong_input() {

		$response = $this->callApiAsAuthUser('POST', '/api/organizations/user', ['organizationId' => 'Test', 'userId' => 'test']);

		$response->assertStatus(500);

		$this->assertFalse($response['success']);

		$this->assertDatabaseMissing('organization_user', ['organizationId' => 'Test', 'userId' => '12']);

	}

	public function test_user_can_be_associated_with_organization() {

		$userId = User::factory()->create()->id;
		$organizationId = Organization::factory()->create()->id;

		$response = $this->callApiAsAuthUser('POST', '/api/organizations/user', ['organizationId' => $organizationId, 'userId' => $userId]);

		$response->assertStatus(201);

		$this->assertTrue($response['success']);

		$this->assertDatabaseHas('organization_user', ['organizationId' => $organizationId, 'userId' => $userId]);

	}

	public function test_user_can_be_removed_from_organization() {

		$userId = User::factory()->create()->id;
		$organizationId = Organization::factory()->create()->id;

		OrganizationUser::factory()->create(['organizationId' => $organizationId, 'userId' => $userId]);

		$response = $this->callApiAsAuthUser('POST', '/api/organizations/user/delete', ['organizationId' => $organizationId, 'userId' => $userId]);

		$response->assertStatus(200);

		$this->assertTrue($response['success']);

		$this->assertDatabaseMissing('organization_user', ['organizationId' => $organizationId, 'userId' => $userId]);

	}

	public function test_user_can_not_be_removed_from_organization() {

		$userId = User::factory()->create()->id;
		$organizationId = Organization::factory()->create()->id;

		OrganizationUser::factory()->create(['organizationId' => $organizationId, 'userId' => $userId]);

		$response = $this->callApiAsAuthUser('POST', '/api/organizations/user/delete', ['organizationId' => '1231321312', 'userId' => '21312312']);

		$response->assertStatus(500);

		$this->assertFalse($response['success']);

		$this->assertDatabaseHas('organization_user', ['organizationId' => $organizationId, 'userId' => $userId]);

	}

	public function test_user_can_remove_locale_from_organization() {

		$localeId = Locale::factory()->create()->id;
		$organizationId = Organization::factory()->create()->id;

		LocaleOrganization::factory()->create(['organizationId' => $organizationId, 'localeId' => $localeId]);

		$response = $this->callApiAsAuthUser('POST', '/api/organizations/locale/delete', ['organizationId' => $organizationId, 'localeId' => $localeId]);

		$response->assertStatus(200);

		$this->assertTrue($response['success']);

		$this->assertDatabaseMissing('locale_organization', ['organizationId' => $organizationId, 'localeId' => $localeId]);

	}

	public function test_user_can_not_remove_locale_from_organization_with_wrong_input() {

		$localeId = Locale::factory()->create()->id;
		$organizationId = Organization::factory()->create()->id;

		LocaleOrganization::factory()->create(['organizationId' => $organizationId, 'localeId' => $localeId]);

		$response = $this->callApiAsAuthUser('POST', '/api/organizations/locale/delete', ['organizationId' => '123213', 'localeId' => '12312321']);

		$response->assertStatus(500);

		$this->assertFalse($response['success']);

		$this->assertDatabaseHas('locale_organization', ['organizationId' => $organizationId, 'localeId' => $localeId]);

	}

	public function test_user_can_add_locale_to_organization() {

		$localeId = Locale::factory()->create()->id;
		$organizationId = Organization::factory()->create()->id;

		$response = $this->callApiAsAuthUser('POST', '/api/organizations/locale', ['organizationId' => $organizationId, 'localeId' => $localeId]);

		$response->assertStatus(201);

		$this->assertTrue($response['success']);

		$this->assertDatabaseHas('locale_organization', ['organizationId' => $organizationId, 'localeId' => $localeId]);

	}

	public function test_user_can_not_add_locale_to_organization_with_wrong_input() {

		$response = $this->callApiAsAuthUser('POST', '/api/organizations/locale', ['organizationId' => '12312312', 'localeId' => '123123123121']);

		$response->assertStatus(500);

		$this->assertFalse($response['success']);

		$this->assertDatabaseMissing('locale_organization', ['organizationId' => '123123', 'localeId' => '1231231']);

	}

	public function test_user_can_get_organization_by_id() {

		$response = $this->callApiAsAuthUser('GET', '/api/organizations/' . $this->organization->id);

		$response->assertStatus(200);

		$this->assertTrue($response['success']);

	}

	public function test_user_can_not_get_organization_by_id_with_wrong_input() {

		$response = $this->callApiAsAuthUser('GET', '/api/organizations/12321321312');

		$response->assertStatus(404);

	}

	public function test_user_can_create_api_key_for_organization() {

		$project = Project::factory()->create();

		$response = $this->callApiAsAuthUser('POST', '/api/organizations/settings/api-keys', [
			'organizationId' => $this->organization->id,
			'projectId' => $project->id,
		]);

		$response->assertStatus(201);

		$this->assertTrue($response['success']);

		$this->assertDatabaseHas('organization_project_key', ['organizationId' => $this->organization->id, 'projectId' => $project->id]);

	}

	public function test_user_can_not_create_api_key_for_organization_with_wrong_input() {

		$project = Project::factory()->create();

		$response = $this->callApiAsAuthUser('POST', '/api/organizations/settings/api-keys', [
			'organizationId' => 12312312,
			'projectId' => '12312312'
		]);

		$response->assertStatus(500);

		$this->assertFalse($response['success']);

		$this->assertDatabaseMissing('organization_project_key', ['organizationId' => $this->organization->id, 'projectId' => $project->id]);

	}
}