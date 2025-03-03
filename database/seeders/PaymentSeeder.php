<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payments = [
            ['amount' => 374.00, 'description' => 'Transaction for Booking #232', 'user_id' => 246, 'payment_method_id' => 1, 'payment_status_id' => 1, 'created_at' => now()],
            ['amount' => 360.00, 'description' => 'Transaction for Booking #233', 'user_id' => 246, 'payment_method_id' => 1, 'payment_status_id' => 1, 'created_at' => now()],
            ['amount' => 2.20, 'description' => 'Transaction for Booking #234', 'user_id' => 246, 'payment_method_id' => 1, 'payment_status_id' => 1, 'created_at' => now()],
            ['amount' => 48.00, 'description' => 'Transaction for Booking #240', 'user_id' => 236, 'payment_method_id' => 1, 'payment_status_id' => 1, 'created_at' => now()],
            ['amount' => 220.00, 'description' => 'Transaction for Booking #245', 'user_id' => 254, 'payment_method_id' => 1, 'payment_status_id' => 1, 'created_at' => now()],
            ['amount' => 110.00, 'description' => 'Transaction for Booking #253', 'user_id' => 266, 'payment_method_id' => 1, 'payment_status_id' => 1, 'created_at' => now()],
            ['amount' => 134.03, 'description' => '926c8263-a2f0-43c5-bc51-940ae9280e48', 'user_id' => 231, 'payment_method_id' => 15, 'payment_status_id' => 2, 'created_at' => now()],
            ['amount' => 24.20, 'description' => 'a526c8fc-7813-41d5-8164-0dd5dae7692a', 'user_id' => 236, 'payment_method_id' => 15, 'payment_status_id' => 2, 'created_at' => now()],
            ['amount' => 134.03, 'description' => '16744c24-29ad-4847-9a5d-4f940a6a4f11', 'user_id' => 231, 'payment_method_id' => 16, 'payment_status_id' => 2, 'created_at' => now()],
            ['amount' => 660.00, 'description' => 'a6672bf6-6ac4-48fd-b54c-ef1ad121db93', 'user_id' => 268, 'payment_method_id' => 16, 'payment_status_id' => 2, 'created_at' => now()],
        ];

        foreach ($payments as $payment) {
            $payment['updated_at'] = $payment['created_at'];
        }

        DB::table('payments')->insert($payments);
    }
}
