<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Account;
use App\Models\User;
use App\Models\WorkoutUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkoutUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        WorkoutUser::truncate();

        $workoutUsers = WorkoutUser::factory()->count(3)
            ->for(User::factory())
            ->create();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
