<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CertificateType;

class CertificateTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'name' => 'Food Safety Certificate',
                'description' => 'Certificate for food safety compliance',
                'is_active' => true,
            ],
            [
                'name' => 'Health Permit',
                'description' => 'Health department permit for restaurant operations',
                'is_active' => true,
            ],
            [
                'name' => 'Business License',
                'description' => 'Official business license for restaurant',
                'is_active' => true,
            ],
            [
                'name' => 'Hygiene Certificate',
                'description' => 'Hygiene and sanitation compliance certificate',
                'is_active' => true,
            ],
            [
                'name' => 'Insurance Certificate',
                'description' => 'Business insurance coverage certificate',
                'is_active' => true,
            ],
            [
                'name' => 'Fire Safety Certificate',
                'description' => 'Fire safety compliance certificate',
                'is_active' => true,
            ],
            [
                'name' => 'Environmental Permit',
                'description' => 'Environmental compliance permit',
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            CertificateType::create($type);
        }
    }
}
