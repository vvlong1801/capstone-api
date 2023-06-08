<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Account;
use App\Models\Creator;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $creator = Creator::factory()->for(
            User::factory()->state([
                'name' => 'creator',
                'email' => 'creator1@creator.com',
            ])->for(
                Account::factory()->state(['role' => Role::creator])
            )
        )->create();
    }
}
