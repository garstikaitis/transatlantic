<?php

namespace Database\Factories;

use App\Models\Locale;
use App\Models\Project;
use App\Models\Organization;
use App\Models\OrganizationProjectKey;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationProjectKeyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrganizationProjectKey::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'organizationId' => Organization::factory()->create(),
			'projectId' => Project::factory()->create(),
			'key' => $this->faker->uuid,
        ];
    }
}
