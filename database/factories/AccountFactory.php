<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Enums\StatusAccount;
use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Account::class;

    public function definition()
    {
        return [
            'role' => Role::workoutUser,
            'status' => StatusAccount::verified,
        ];
    }
}
