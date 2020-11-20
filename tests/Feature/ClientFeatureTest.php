<?php

namespace Tests\Feature;

use App\Models\Locale;
use App\Models\LocaleProject;
use App\Models\Translation;
use Tests\TestCase;

class ClientFeatureTest extends TestCase
{

	public function setUp(): void {

		parent::setUp();

		$this->withoutMiddleware();

	}

    public function test_user_can_get_client_translations()
    {

		$this->createMockedTranslations();

        $response = $this->json('GET', '/api/client/translations', [
			'organizationId' => $this->organization->id,
			'projectId' => $this->project->id,
			'token' => $this->token->key,
		]);

		$response->assertStatus(200);

	}

    public function test_user_can_not_get_client_translations_with_wrong_input()
    {

        $response = $this->json('GET', '/api/client/translations', [
			'organizationId' => '123',
			'projectId' => 21312321,
			'token' => $this->token->key,
		]);

		$response->assertStatus(500);

	}

    public function test_user_can_not_get_client_translations_with_wrong_token()
    {

        $response = $this->json('GET', '/api/client/translations', [
			'organizationId' => $this->organization->id,
			'projectId' => $this->project->id,
			'token' => '12312312321',
		]);

		$response->assertStatus(500);

		$this->assertTrue($response['message'] === 'Token is not authorized for this project');

	}

	public function createMockedTranslations() {

		$locale1 = Locale::factory()->create([
			'name' => 'English',
			'iso' => 'en'
		]);
		$locale2 = Locale::factory()->create([
			'name' => 'Danish',
			'iso' => 'da'
		]);
		$locale3 = Locale::factory()->create([
			'name' => 'Spanish',
			'iso' => 'es'
		]);

		LocaleProject::factory()->create([
			'localeId' => $locale1->id,
			'projectId' => $this->project->id,
			'isMainLocale' => false,
		]);
		LocaleProject::factory()->create([
			'localeId' => $locale2->id,
			'projectId' => $this->project->id,
			'isMainLocale' => false,
		]);
		LocaleProject::factory()->create([
			'localeId' => $locale3->id,
			'projectId' => $this->project->id,
			'isMainLocale' => false,
		]);

		Translation::factory()->create([
			'transKey' => 'general.hello.email',
			'organizationId' => $this->organization->id,
			'projectId' => $this->project->id,
			'localeId' => $locale1->id,
		]);
		Translation::factory()->create([
			'transKey' => 'general.hello.email',
			'organizationId' => $this->organization->id,
			'projectId' => $this->project->id,
			'localeId' => $locale2->id,
		]);
		Translation::factory()->create([
			'transKey' => 'general.ok',
			'organizationId' => $this->organization->id,
			'projectId' => $this->project->id,
			'localeId' => $locale2->id,
		]);
		Translation::factory()->create([
			'transKey' => 'general.success',
			'organizationId' => $this->organization->id,
			'projectId' => $this->project->id,
			'localeId' => $locale3->id,
		]);

	}
	
}
