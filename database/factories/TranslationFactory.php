<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Model;
use App\Models\Locale;
use Illuminate\Support\Str;
use App\Models\Organization;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

class TranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'transKey' => Str::random(5),
            'transValue' => $this->faker->text(),
            'localeId' => Locale::factory()->create()->id,
            'organizationId' => Organization::factory()->create()->id,
            'userId' => User::factory()->create()->id
        ];
    }
}
