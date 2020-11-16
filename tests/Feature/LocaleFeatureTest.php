<?php

namespace Tests\Feature;

use App\Models\Locale;
use Tests\TestCase;

class LocaleFeatureTest extends TestCase
{

	public function setUp(): void {

		parent::setUp();

		$this->withoutMiddleware();

	}

    public function test_user_can_get_all_locales()
    {

		Locale::factory()->create();

        $response = $this->callApiAsAuthUser('GET', '/api/locales');

		$response->assertStatus(200);

		$response->assertJsonCount(1, 'data');

	}
	
}
