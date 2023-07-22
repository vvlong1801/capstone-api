<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Account;
use App\Models\Creator;
use App\Models\Member;
use App\Models\User;
use App\Models\WorkoutUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();

        $superAdmin = User::factory()->count(1)->state([
            'name' => 'admin',
            'email' => 'superAdmin@admin.com',
        ])->for(Account::factory()->state(['role' => Role::superAdmin]))->create();

        $admin = User::factory()->count(1)->state([
            'name' => 'admin',
            'email' => 'admin@admin.com',
        ])->for(Account::factory()->state(['role' => Role::admin]))->create();

        $this->call([CreatorSeeder::class, WorkoutUserSeeder::class]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
