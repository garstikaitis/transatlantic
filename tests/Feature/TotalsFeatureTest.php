<?php

namespace Tests\Feature;

use App\Models\Translation;
use Tests\TestCase;

class TotalsFeatureTest extends TestCase
{

	public function setUp(): void {

		parent::setUp();

		$this->withoutMiddleware();

	}

    public function test_user_can_get_totals()
    {

		for($i = 0; $i < 20; $i++) {
			Translation::factory(['organizationId' => $this->organization->id])->create();
		}


        $response = $this->callApiAsAuthUser('GET', '/api/totals', ['organizationId' => $this->organization->id]);

		$response->assertStatus(200);

		$this->assertTrue($response['data']['total_translations'] === 20);

	}

    public function test_user_can_not_get_totals_with_wrong_input()
    {

		for($i = 0; $i < 20; $i++) {
			Translation::factory(['organizationId' => $this->organization->id])->create();
		}


        $response = $this->callApiAsAuthUser('GET', '/api/totals', ['organizationId' =>12321312]);

		$response->assertStatus(500);

		$this->assertFalse($response['success']);

	}
	
}
