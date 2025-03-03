<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'id' => 1,
                'name' => json_encode(['en' => 'Cash']),
                'description' => json_encode(['en' => 'Click to pay cash when finish']),
                'route' => '/Cash',
                'order' => 3,
                'default' => false,
                'enabled' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 15,
                'name' => json_encode(['en' => 'N-genius']),
                'description' => json_encode(['en' => 'N-genius Payment Gateway UAE']),
                'route' => '/payments/ngenius/checkout?booking_id=',
                'order' => 4,
                'default' => false,
                'enabled' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 16,
                'name' => json_encode(['en' => 'Ziina']),
                'description' => json_encode(['en' => 'Ziina payment gateway UAE']),
                'route' => '/ziina',
                'order' => 1,
                'default' => false,
                'enabled' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 17,
                'name' => json_encode(['en' => 'stripe']),
                'description' => json_encode(['en' => 'stripe']),
                'route' => 'stripe',
                'order' => 9,
                'default' => true,
                'enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
