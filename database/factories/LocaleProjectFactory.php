<?php

namespace Database\Factories;

use App\Models\Locale;
use App\Models\LocaleProject;
use App\Models\Organization;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocaleProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LocaleProject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'projectId' => Project::factory()->create()->id,
            'localeId' => Locale::factory()->create()->id
        ];
    }
}
