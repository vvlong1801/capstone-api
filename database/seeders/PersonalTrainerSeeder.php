<?php

namespace Database\Seeders;

use App\Models\PersonalTrainer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalTrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PersonalTrainer::truncate();

        for ($i = 1; $i < 5; $i++) {
            $certificate = $i % 3 == 0;
            $issuer = $i % 3 + 1;
            PersonalTrainer::create([
                'certificate' => $certificate,
                'creator_id' => $i,
                'certificate_issuer_id' => $issuer,
                'ID_number' => "00" . $i . "123456789",
                'address' => "address " . $i,
                'verified_at' => \Carbon\Carbon::now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
