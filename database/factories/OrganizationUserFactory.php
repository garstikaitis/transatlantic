<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Model;
use App\Enums\RoleEnum;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Enums\InvitationStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrganizationUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'userId' => User::factory()->create()->id,
            'organizationId' => Organization::factory()->create()->id,
            'invitation_status' => InvitationStatusEnum::PENDING,
            'role' => RoleEnum::VIEWER,
        ];
    }
}
