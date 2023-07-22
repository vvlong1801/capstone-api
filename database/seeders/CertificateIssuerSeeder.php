<?php

namespace Database\Seeders;

use App\Models\CertificateIssuer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificateIssuerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CertificateIssuer::truncate();

        for ($i = 1; $i < 4; $i++) {
            CertificateIssuer::create([
                'name' => 'certificate_issuer ' . $i,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
