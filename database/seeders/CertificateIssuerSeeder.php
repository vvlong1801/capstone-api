<?php

namespace Database\Seeders;

use App\Models\CertificateIssuer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CertificateIssuerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 4; $i++) {
            CertificateIssuer::create([
                'name' => 'certificate_issuer ' . $i,
            ]);
        }
    }
}
