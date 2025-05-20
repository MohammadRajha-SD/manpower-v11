<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProviderRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provider_requests')->insert([
            [
                'company_name' => 'Alpha Tech',
                'company_website' => 'https://alphatech.com',
                'contact_person' => 'John Doe',
                'contact_email' => 'john@alphatech.com',
                'phone_number' => '1234567890',
                'number_employees' => 25,
                'cities' => json_encode(['New York', 'Los Angeles']),
                'services' => json_encode(['Hosting', 'Security']),
                'plans' => json_encode(['Basic', 'Premium']),
                'notes' => 'Looking to expand in North America.',
                'licence' => 'uploads/pos-demo.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'Beta Solutions',
                'company_website' => 'https://betasolutions.org',
                'contact_person' => 'Jane Smith',
                'contact_email' => 'jane@betasolutions.org',
                'phone_number' => '0987654321',
                'number_employees' => 50,
                'cities' => json_encode(['Chicago']),
                'services' => json_encode(['Cloud Storage']),
                'plans' => json_encode(['Enterprise']),
                'notes' => 'Requesting a demo plan.',
                'licence' => 'uploads/pos-demo.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
