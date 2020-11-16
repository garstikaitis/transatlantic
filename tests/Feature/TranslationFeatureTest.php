<?php

namespace Tests\Feature;

use App\Models\Locale;
use App\Models\LocaleOrganization;
use App\Models\Translation;
use Tests\TestCase;

class TranslationFeatureTest extends TestCase
{

	public function setUp(): void {

		parent::setUp();

		$this->withoutMiddleware();

	}

    public function test_user_can_get_translations()
    {

		$locale1 = Locale::factory()->create(['iso' => 'en']);
		$locale2 = Locale::factory()->create(['iso' => 'da']);

		
		Translation::factory()->create([
			'localeId' => $locale1->id,
			'organizationId' => $this->organization->id,
			'userId' => $this->user->id,
			]);
		Translation::factory()->create([
			'localeId' => $locale2->id,
			'organizationId' => $this->organization->id,
			'userId' => $this->user->id,
		]);

        $response = $this->callApiAsAuthUser('POST', '/api/translations', [
			'locales' => [$locale1->iso],
			'organizationId' => $this->organization->id
		]);

		$response->assertStatus(200);

		$response->assertJsonCount(1, 'data');

	}

    public function test_user_can_not_get_translations_with_wrong_input()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/translations', [
			'locales' => 123213,
			'organizationId' => $this->organization->id
		]);

		$response->assertStatus(500);

	}

    public function test_user_can_not_get_translations_with_wrong_locale_iso_code()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/translations', [
			'locales' => ['12312'],
			'organizationId' => $this->organization->id
		]);

		$response->assertStatus(500);

	}

	public function test_user_not_can_create_translation() {

		$locale = Locale::factory()->create(['iso' => 'en']);

		$response = $this->callApiAsAuthUser('POST', '/api/translations/create', [
			'transValue' => 'abc',
			'localeId' => $locale->id,
			'organizationId' => $this->organization->id,
			'userId' => $this->user->id,
		]);

		$response->assertStatus(500);

		$this->assertDatabaseMissing('translations', ['transKey' => 123]);

	}

	public function test_user_not_can_create_translation_with_locale_that_is_not_associated_with_company() {

		$locale = Locale::factory()->create(['iso' => 'en']);

		// LocaleOrganization::factory()->create(['localeId' => $locale->id, 'organizationId' => $this->organization->id]);

		$response = $this->callApiAsAuthUser('POST', '/api/translations/create', [
			'transKey' => '123',
			'transValue' => 'abc',
			'localeId' => $locale->id,
			'organizationId' => $this->organization->id,
			'userId' => $this->user->id,
		]);

		$response->assertStatus(500);

		$this->assertFalse($response['success']);
		
		$this->assertTrue($response['message'] === 'Locale is disabled for organization');

		$this->assertDatabaseMissing('translations', ['transKey' => 123]);

	}

	public function test_user_can_create_translation() {

		$locale = Locale::factory()->create(['iso' => 'en']);

		LocaleOrganization::factory()->create(['localeId' => $locale->id, 'organizationId' => $this->organization->id]);

		$response = $this->callApiAsAuthUser('POST', '/api/translations/create', [
			'transKey' => '123',
			'transValue' => 'abc',
			'localeId' => $locale->id,
			'organizationId' => $this->organization->id,
			'userId' => $this->user->id,
		]);

		$response->assertStatus(201);

		$this->assertTrue($response['success']);
		
		$this->assertDatabaseHas('translations', ['transKey' => 123]);

	}
	
}
