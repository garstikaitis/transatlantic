<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\Locale;
use App\Models\LocaleOrganization;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocaleOrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LocaleOrganization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'localeId' => Locale::factory()->create()->id,
            'organizationId' => Organization::factory()->create()->id,
        ];
    }
}
