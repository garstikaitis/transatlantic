<?php

namespace Tests\Feature;

use App\Enums\RoleEnum;
use App\Models\Locale;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{

	public function setUp(): void {

		parent::setUp();

		$this->withoutMiddleware();

	}

    public function test_user_can_update_user()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/users/update', [
			'userId' => $this->user->id,
			'firstName' => 'John',
			'lastName' => 'Travolta',
			'onboardingCompleted' => false,
			'email' => '123@gmail.com',
			'role' => RoleEnum::VIEWER,
			'newLogo' => 'null'
		]);

		$response->assertStatus(200);


		$this->assertDatabaseHas('users', ['id' => $this->user->id, 'firstName' => 'John']);

	}

    public function test_user_can_update_user_with_wrong_input()
    {

        $response = $this->callApiAsAuthUser('POST', '/api/users/update', [
			'userId' => '12321312312',
			'firstName' => 'John',
			'lastName' => 'Travolta',
			'onboardingCompleted' => false,
			'email' => '123@gmail.com'
		]);

		$response->assertStatus(500);

		$this->assertDatabaseMissing('users', ['firstName' => 'John']);

	}
	
}
