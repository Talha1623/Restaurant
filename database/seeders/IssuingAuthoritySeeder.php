<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IssuingAuthority;

class IssuingAuthoritySeeder extends Seeder
{
    public function run()
    {
        $authorities = [
            [
                'name' => 'Health Department',
                'description' => 'Local health department for food safety compliance',
                'is_active' => true,
            ],
            [
                'name' => 'Food Safety Authority',
                'description' => 'National food safety regulatory body',
                'is_active' => true,
            ],
            [
                'name' => 'Business Registration Office',
                'description' => 'Government office for business licensing',
                'is_active' => true,
            ],
            [
                'name' => 'Fire Department',
                'description' => 'Fire safety and emergency services department',
                'is_active' => true,
            ],
            [
                'name' => 'Environmental Protection Agency',
                'description' => 'Environmental compliance and protection authority',
                'is_active' => true,
            ],
            [
                'name' => 'Insurance Company',
                'description' => 'Business insurance provider',
                'is_active' => true,
            ],
            [
                'name' => 'Hygiene Inspectorate',
                'description' => 'Hygiene and sanitation compliance authority',
                'is_active' => true,
            ],
        ];

        foreach ($authorities as $authority) {
            IssuingAuthority::create($authority);
        }
    }
}
