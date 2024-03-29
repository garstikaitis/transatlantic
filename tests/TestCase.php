<?php

namespace Tests;

use App\Models\User;
use App\Models\Locale;
use App\Models\Project;
use App\Models\Organization;
use App\Models\OrganizationProjectKey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Factories\OrganizationProjectKeyFactory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public User $user;
    public Organization $organization;
    public Locale $locale;
    public Project $project;
    public OrganizationProjectKey $token;

    public function setUp(): void {

        parent::setUp();

        $this->setUpUser();

        $this->setUpOrganization();

        $this->setUpLocale();

        $this->setUpProject();

        $this->setUpToken();

    }

    private function setUpToken() {

        $this->token = OrganizationProjectKey::factory()->create([
            'projectId' => $this->project->id,
            'organizationId' => $this->organization->id,
        ]);

    }

    private function setUpUser() {

        $this->user = User::factory()->create(['email' => 'test@example.com', 'password' => 'password']);

    }

    private function setUpProject() {

        $this->project = Project::factory()->create();

    }

    private function setUpLocale() {

        $this->locale = Locale::factory()->create();

    }

    private function setUpOrganization() {

        $this->organization = Organization::factory()->create();

    }

    public function callApiAsAuthUser($type = 'POST', $endpoint, $params = [], $asUser = 'test@example.com') {

        $endpoint = $this->starts_with($endpoint, '/')
            ? $endpoint
            : '/' . $endpoint;

        $headers = [];

        if (!is_null($asUser)) {
            $token = auth()->guard('api')
                ->login(User::whereEmail($asUser)->first());

            $headers['Authorization'] = 'Bearer ' . $token;
        }

        return $this->json(
            $type, 
            env('APP_URL') . $endpoint,
            $params,
            $headers
        );
    }

    private function starts_with($haystack, $needle) {	
	    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

}
