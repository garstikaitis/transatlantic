<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthFeatureTest extends TestCase
{

	public function setUp(): void {

		parent::setUp();

		$this->withoutMiddleware();

	}

    public function test_user_can_get_token()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/login', ['email' => $this->user->email, 'password' => 'password']);

		$response->assertStatus(200);
		
		$json = $response->decodeResponseJson();

		$this->assertTrue($json['data']['token_type'] === 'bearer');
	}
	
    public function test_user_can_not_get_token()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/login', ['email' => $this->user->email, 'password' => 'password23423423']);

		$response->assertStatus(401);
		
	}
	
	public function test_user_can_not_logout_if_not_logged_in() {

		$response = $this->json('POST', '/api/auth/logout');

		$response->assertStatus(500);

	}

	public function test_user_can_logout() {

		$response = $this->callApiAsAuthUser('POST', '/api/auth/logout');

		$response->assertStatus(200);

		$json = $response->decodeResponseJson();

		$this->assertTrue($json['message'] === 'Successfully logged out');

	}

	public function test_user_can_get_me() {

		$response = $this->callApiAsAuthUser('POST', '/api/auth/me');

		$response->assertStatus(200);

	}
}
