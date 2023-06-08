<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Account;
use App\Models\User;
use App\Models\WorkoutUser;
use Illuminate\Database\Seeder;

class WorkoutUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workoutUsers = WorkoutUser::factory()->count(3)
            ->for(User::factory())
            ->create();
    }
}
