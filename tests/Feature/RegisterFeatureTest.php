<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterFeatureTest extends TestCase
{

	public function setUp(): void {

		parent::setUp();

		$this->withoutMiddleware();

	}

    public function test_user_can_register()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/register', ['email' => 'email@email.com', 'password' => 'password']);

		$response->assertStatus(200);
		
		$json = $response->decodeResponseJson();

		$this->assertTrue($json['data']['token_type'] === 'bearer');
	}
	
    public function test_user_can_not_get_token()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/login', ['email' => 123123, 'password' => 'password23423423']);

		$response->assertStatus(401);
		
	}
	
}
