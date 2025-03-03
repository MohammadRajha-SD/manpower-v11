<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['created_at' => now(), 'updated_at' => now(), 'id' => 1, 'status' => 'Received', 'order' => 1],
            ['created_at' => now(), 'updated_at' => now(), 'id' => 2, 'status' => 'In Progress', 'order' => 40],
            ['created_at' => now(), 'updated_at' => now(), 'id' => 3, 'status' => 'On the Way', 'order' => 20],
            ['created_at' => now(), 'updated_at' => now(), 'id' => 5, 'status' => 'Ready', 'order' => 30],
            ['created_at' => now(), 'updated_at' => now(), 'id' => 6, 'status' => 'Done', 'order' => 50],
            ['created_at' => now(), 'updated_at' => now(), 'id' => 7, 'status' => 'Failed', 'order' => 60],
        ];

        DB::table('booking_statuses')->insert($statuses);
    }
}
