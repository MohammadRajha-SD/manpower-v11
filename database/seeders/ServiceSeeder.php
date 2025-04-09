<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Coupon;
use Carbon\Carbon;
use App\Models\Booking;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'id'=>1,
            'category_id' => 1,
            'provider_id' => 3,
            'name' => 'Home Cleaning',
            'description' => 'Basic home cleaning service.',
            'discount_price' => 80,
            'price' => 100,
            'price_unit' => 'hourly',
            'quantity_unit' => 1,
            'duration' => now(),
            'featured' => true,
            'enable_booking' => true,
            'available' => true,
        ]);

        Service::create([
            'id'=>2,
            'category_id' => 2,
            'provider_id' => 5,
            'name' => 'Car Wash',
            'description' => 'Exterior and interior car wash.',
            'discount_price' => 50,
            'price' => 70,
            'price_unit' => 'fixed',
            'quantity_unit' => 1,
            'duration' => now(),
            'featured' => false,
            'enable_booking' => true,
            'available' => true,
        ]);
 
        Coupon::create([
            'code' => 'DISCOUNT10',
            'discount' => 10,
            'discount_type' => 'percent',
            'description' => '10% off on any service',
            'expires_at' => Carbon::now()->addDays(30),
            'enabled' => 1,
        ]);

        Coupon::create([
            'code' => 'SAVE20',
            'discount' => 20,
            'discount_type' => 'fixed',
            'description' => 'Save AED 20 on orders over AED 100',
            'expires_at' => Carbon::now()->addDays(15),
            'enabled' => 1,
        ]);

        $coupon = Coupon::where('code', 'DISCOUNT10')->first();

        Booking::create([
            'quantity' => 2,
            'coupon' => $coupon->code,
            'cancel' => 0,
            'hint' => 'Please come on time.',
            'booking_at' => Carbon::now(),
            'start_at' => Carbon::now()->addDays(2),
            'ends_at' => Carbon::now()->addDays(2)->addHours(2),
            'address' => 'abu-dabhi',
            'service_id' => 1,
            'booking_status_id' => 1,
            'provider_id' => 3,
            'payment_id' => 1,
            'tax_id' => 1,
        ]);
    }
}
